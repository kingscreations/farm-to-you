<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 2/11/2015
 * Time: 2:49 PM
 */
require_once("../classes/user.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

//generate salt, hash, and activation
$salt = bin2hex(openssl_random_pseudo_bytes(32));
$hash = hash_pbkdf2("sha512", "password", $salt, 2048, 128);
$activation = bin2hex(openssl_random_pseudo_bytes(16));




try {
//	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$user = new User(null, "$inputEmail", $hash, $salt, $activation);
	$user->insert($mysqli);

	if(@isset($_POST["inputEmail"]) && ($_POST["password"])) {
		$user = new User(null, $_POST["inputEmail"], $hash, $salt, $activation, $user->getUserId());
	}

	$user->insert($mysqli);
	echo "<p class=\"success!\">User (id = " . $user->getUserId() . ") posted!</p>";
} catch(Exception $exception) {
	echo "<p class=\"input not posted!\">Exception: " . $exception->getMessage() . "</p>";
}