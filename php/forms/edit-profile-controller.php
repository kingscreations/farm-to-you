<?php
$currentDir = dirname(__FILE__);

require_once ("../../root-path.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../../dummy-session-single.php");
require_once("../classes/profile.php");
require_once("../lib/utils.php");


// verify the form values have been submitted
if(@isset($_POST["inputFirstname"]) === false || @isset($_POST["inputLastname"]) === false
	|| @isset($_POST["inputType"]) === false || @isset($_POST["inputPhone"]) === false)  {
	echo "<p class=\"alert alert-danger\">Form values not complete. Verify the form and try again.</p>";
}



try {
	//
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$profileId = $_SESSION['profile']['id'];
	$userId = $_SESSION['user']['id'];

	if(@isset($_POST["inputImage"])) {
		// the 13 and 25 are holders for profile id of 17 and user id of 25
		$profile = new Profile($profileId, $_POST["inputFirstname"], $_POST["inputLastname"], $_POST["inputPhone"], $_POST["inputType"], "012345", $_POST["inputImage"], $userId);
	} else {
		$profile = new Profile($profileId, $_POST["inputFirstname"], $_POST["inputLastname"], $_POST["inputPhone"], $_POST["inputType"], "012345", null, $userId);

	}

	if(@isset($_FILES['inputImage'])) {
		$imageBasePath = '/var/www/html/farm-to-you/images/profile/';
		$imageExtension = checkInputImage($_FILES['inputImage']);
		$profile->update($mysqli);
		$profileId = $profile->getProfileId();
		$imageFileName = $imageBasePath . 'profile-' . $profileId . '.' . $imageExtension;
		$profile->setImagePath($imageFileName);
		$profile->update($mysqli);
		move_uploaded_file($_FILES['inputImage']['tmp_name'], $imageFileName);
	} else {
		$profile->setImagePath(null);
		$profile->update($mysqli);
		$profileId = $profile->getProfileId();
	}

	echo "<p class=\"alert alert-success\">Profile (id = " . $profile->getProfileId() . ") updated!</p>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}
