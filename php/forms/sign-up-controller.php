<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 2/11/2015
 * Time: 2:49 PM
 */
require_once("../classes/user.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");


// verify the form values have been submitted
if(@isset($_POST["email"]) === false || @isset($_POST["password"]) === false) {
	echo "<p class=\"alert alert-danger\">form values not complete. Verify the form and try again.</p>";
}

try {
//
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$user = new User(null, "@test.com", $randHash, $randSalt, $randActivation);
	$user->insert($mysqli);

	if(@isset($_POST["inputEmail"]) && ($_POST["password"])) {
		$user = new User(null, $_POST["inputEmail"], $_POST["inputHash"], $_POST["inputSalt"], $_POST["inputActivation"], $user->getUserId());
	}

	$profile->insert($mysqli);
	echo "<p class=\"alert alert-success\">User (id = " . $user->getUserId() . ") posted!</p>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}