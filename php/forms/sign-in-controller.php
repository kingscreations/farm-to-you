<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 2/11/2015
 * Time: 2:49 PM
 */

require_once("../classes/user.php");
require_once("../classes/profile.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
// require CSRF protection
require_once("../lib/csrf.php");
require_once('../../paths.php');

// CSRF requires sessions
session_start();

try {
	if(!@isset($_POST["email"]) || !@isset($_POST["password2"])) {
		throw new Exception('invalid input post');
	}
	// verify the CSRF tokens
	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
	}
// connect to database
	try {
		mysqli_report(MYSQLI_REPORT_STRICT);
		$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
		$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	} catch(Exception $exception) {
		echo "Exception: " . $exception->getMessage() . "<br/>";
		echo $exception->getFile() . ":" . $exception->getLine();
	}
	// filter _POSTed email variable
	$email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);

	// search for the login email from mysqli
	$user = User::getUserByEmail($mysqli, $email);
	$profile = Profile::getProfileByUserId($mysqli, $user->getUserId());

	//get the mysqli hash and salt
	$mysqlSalt = $user->getSalt();
	$mysqlHash = $user->getHash();

	// generate hash from users password using mysqli salt
	$hash = hash_pbkdf2("sha512", $_POST["password2"], $mysqlSalt, 2048, 128);

	// create the url link to the homepage
	$currentPathExploded = explode("/", $_SERVER["PHP_SELF"]);
	if(empty($currentPathExploded)) {
		throw new RangeException('Impossible to explode the path');
	}
	$url = "https://" . $_SERVER["SERVER_NAME"] . '/' . $currentPathExploded[1] . '/' .
		$currentPathExploded[2];


	$_SESSION['emailPop'] = $email;
	// compare hashes
	if($mysqlHash !== $hash) {
		echo "<p class=\"alert alert-danger\">Incorrect Password, please try again!</p>";
		exit();
	}
//	catch any AJAX exceptions
	echo "<div class=\"alert alert-success\" role=\"alert\">You are signed in! Redirecting to home page!</div>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

// create session id specific to this user
	$_SESSION['user'] = array(
		'id' => $user->getUserId()
	);
	$_SESSION['profileId'] = $profile->getProfileId();

?>