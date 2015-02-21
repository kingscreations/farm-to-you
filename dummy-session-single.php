`<?php

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

	$profile = Profile::getProfileByProfileId($mysqli, 1);
	$user = User::getUserByUserId($mysqli, 1);

	$_SESSION['profile'] = array(
		'id' => $profile->getProfileId()
	);
	$_SESSION['user'] = array(
		'id' => $user->getUserId()
	);



	$mysqli->close();

} catch(Exception $exception) {
	echo "fail to connect to the database";
}