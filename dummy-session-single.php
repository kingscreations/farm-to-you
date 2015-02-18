<?php

$dummyPath = dirname(__FILE__);

// Get the relative path
$currentDepth = substr_count($currentDir, "/");
$dummyDepth = substr_count($dummyPath, "/");
$depthDifference = $currentDepth - $dummyDepth;
$prefix = str_repeat("../", $depthDifference);
require_once($prefix.'php/classes/user.php');
require_once($prefix.'php/classes/profile.php');

require_once '/etc/apache2/capstone-mysql/encrypted-config.php';

try {
	mysqli_report(MYSQLI_REPORT_STRICT);

	// get the credentials information from the server
	$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
	$configArray = readConfig($configFile);

	// connection
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	$hash = "12345678123456781234567812345678123456781234567812345678123456781234567812345678123456781234567812345678123456781234567812345678";
	$email = "BillyJoBob@suspender.com";
	$salt = "48121620481216204812162048121620";
	$activation = "1234567812345678";

	$user = new User(1, $email, $hash, $salt, $activation);
	$user->update($mysqli);

	$_SESSION['user'] = array(
		'id' => $user->getUserId()
	);

	$firstName = "John";
	$lastName = "JoBob";
	$phone = "(505)123-4567";
	$profileType = "m";
	$customerToken = "2";
	$imagePath = "clown.jpg";

	$profile = new Profile(1, $firstName, $lastName, $phone, $profileType, $customerToken, $imagePath, $user->getUserId());
	$profile->update($mysqli);

	$_SESSION['profile'] = array(
		'id' => $profile->getProfileId()
	);

	$mysqli->close();

} catch(Exception $exception) {
	echo "fail to connect to the database";
}