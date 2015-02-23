<?php

$dummyPath = dirname(__FILE__);

// Get the relative path
$currentDepth = substr_count($currentDir, "/");
$dummyDepth = substr_count($dummyPath, "/");
$depthDifference = $currentDepth - $dummyDepth;
$prefix = str_repeat("../", $depthDifference);
require_once($prefix.'php/classes/user.php');

require_once '/etc/apache2/capstone-mysql/encrypted-config.php';

function randomString($length = 6) {
	$str = "";
	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}

try {
	mysqli_report(MYSQLI_REPORT_STRICT);

	// get the credentials information from the server
	$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
	$configArray = readConfig($configFile);

	// connection
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	$user = new User(null, randomString(6) . '@' . randomString(8) . '.' . randomString(3),
		bin2hex(openssl_random_pseudo_bytes(64)),
		bin2hex(openssl_random_pseudo_bytes(16)),
		bin2hex(openssl_random_pseudo_bytes(8)));
	$user->insert($mysqli);

	$_SESSION['user'] = array(
		'id' => $user->getUserId()
	);

	$mysqli->close();

} catch(Exception $exception) {
	echo "fail to connect to the database";
}