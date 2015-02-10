<?php
require_once("../php/classes/profile.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");


// verify the form values have been submitted
if(@isset($_POST["InputFirstname"]) === false || @isset($_POST["InputLastname"]) === false
	|| @isset($_POST["InputType"]) === false || @isset($_POST["InputPhone"]) === false)  {
	echo "<p class=\"alert alert-danger\">Form values not complete. Verify the form and try again.</p>";
}

try {
	//
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);
	if(@isset($_POST["InputImage"])) {
		$profile = new Profile(17, $_POST["InputFirstname"], $_POST["InputLastname"], $_POST["InputPhone"], $_POST["InputType"], "012345", $_POST["InputImage"], 25);
	} else {
		$profile = new Profile(17, $_POST["InputFirstname"], $_POST["InputLastname"], $_POST["InputPhone"], $_POST["InputType"], "012345", null, 25);

	}
	$profile->update($mysqli);
	echo "<p class=\"alert alert-success\">Profile (id = " . $profile->getProfileId() . ") updated!</p>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}
