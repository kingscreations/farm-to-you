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

// CSRF requires sessions
session_start();

try {
	if(!@isset($_POST["inputEmail"]) || !@isset($_POST["password"])) {
		throw new Exception('invalid input post');
	}

	// verify the CSRF tokens
	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
	}
	// get the users email from mysqli
	$this->user->insert($this->mysqli);
	$mysqlUser = User::getUserByEmail($this->mysqli, $this->email);
	$this->assertIdentical($this->user->inputEmail(), $mysqlUser->getEmail());

	// generate hash and retrieve salt
	$salt =
	$hash = hash_pbkdf2("sha512", "password", $salt, 2048, 128);

	// retrieve users hash and compare to input

} catch(Exception $exception) {
	echo "<p class=\"input not posted!\">Exception: " . $exception->getMessage() . "</p>";
}