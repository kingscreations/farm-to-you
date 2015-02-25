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
require_once("../lib/csrf.php");
// require PEAR::Mail <http://pear.php.net/package/Mail> to send mail
require_once("Mail.php");

// CSRF requires sessions
//session_start();

// tell controller to retrieve form data
try {
	if(!@isset($_POST["inputEmail"]) || !@isset($_POST["password1"])) {
		throw new Exception('invalid input post');
	}

//generate salt, hash, and activation from input
	$salt = bin2hex(openssl_random_pseudo_bytes(16));
	$hash = hash_pbkdf2("sha512", "password1", $salt, 2048, 128);
	$activation = bin2hex(openssl_random_pseudo_bytes(8));

	// verify the CSRF tokens
//	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
//		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
//	}
	// connect to mysqli
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	// insert user into mysqli
	$user = new User(null, $_POST["inputEmail"], $hash, $salt, $activation);
	$user->insert($mysqli);

	//populate session with activation variable
	$_SESSION['activation'] = $activation;

	// email the user with an activation message
	$to = $_POST["inputEmail"];
	$from = "CEO@farmtoyou.com";

	// build headers
	$headers = array();
	$headers["To"] = $to;
	$headers["From"] = $from;
	$headers["Reply-To"] = $from;
	$headers["Subject"] = "Welcome to Farm To You!";
	$headers["MIME-Version"] = "1.0";
	$headers["Content-Type"] = "text/html; charset=UTF-8";

	// build message
	$currentPathExploded = explode("/", $_SERVER["PHP_SELF"]);
	if(empty($currentPathExploded)) {
		throw new RangeException('Impossible to explode the path');
	}
	$url = "https://". $_SERVER["SERVER_NAME"] . '/' . $currentPathExploded[1] . '/' .
		$currentPathExploded[2] . '/activation/index.php';

	$url = "$url?activation=$activation";
	
	$message = <<< EOF
	<html>
		<body>
			<h1>Welcome to Farm To You!</h1>
			<hr />
			<p>Thank you for creating a password. Visit the following URL to complete your registration process: <a href="$url">$url</a>.</p>
		</body>
	</html>
EOF;

	// send the email
	error_reporting(E_ALL & ~(E_STRICT | E_NOTICE | E_DEPRECATED));
	$mailer =& Mail::factory("sendmail");
	$status = $mailer->send($to, $headers, $message);
	if(PEAR::isError($status) === true)
	{
		echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Jumpin Jehosaphat!</strong> Unable to send mail message:" . $status->getMessage() . "</div>";
	}
	else
	{
		echo "<div class=\"alert alert-success\" role=\"alert\">Sign up successful! Please check your Email to complete the signup process.</div>";
	}
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\" role=\"alert\">Exception: " . $exception->getMessage() . "</p>";
}
