<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 2/11/2015
 * Time: 2:49 PM
 */

require_once("../classes/user.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
// require CSRF protection
// require_once("../lib/csrf.php");

// CSRF requires sessions
session_start();

try {
	if(!@isset($_POST["email"]) || !@isset($_POST["password"])) {
		throw new Exception('invalid input post');
	}

	// verify the CSRF tokens
//	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
//		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
//	}


//$_SESSION['user'] = array(
//	'id' => $user->getUserId()
//);


		// get the users email from mysqli
	$mysqlUser = User::getUserByEmail($this->mysqli, $this->email);
	$this->assertIdentical($this->user->email(), $mysqlUser->getEmail());

	$user = getUserByEmail();

	// generate hash and retrieve salt
	$salt = $this->mysqlUser->getUserSalt;
	$hash = hash_pbkdf2("sha512", "password", $salt, 2048, 128);

	// retrieve users hash and compare to input
	$userHash = $this->mysqlUser->getUserHash;
	if ($userHash !== $hash) {
		throw new Exception('email input does not match existing account');
	}
	// catch any exceptions
} catch(Exception $exception) {
	echo "<p class=\"input not posted!\">Exception: " . $exception->getMessage() . "</p>";
}