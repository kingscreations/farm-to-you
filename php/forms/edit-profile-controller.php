<?php
$currentDir = dirname(__FILE__);
session_start();
require_once ("../../root-path.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
//require_once("../../dummy-session-single.php");
require_once("../classes/profile.php");
require_once("../lib/utils.php");

// verify the form values have been submitted
if(@isset($_POST["inputFirstname"]) === false || @isset($_POST["inputLastname"]) === false
 || @isset($_POST["inputPhone"]) === false)  {
	echo "<p class=\"alert alert-danger\">Form values not complete. Verify the form and try again.</p>";
}

try {
	//
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

$profileId = $_SESSION['profileId'];

	$profile = Profile::getProfileByProfileId($mysqli, $profileId);
	$profileType = $profile->getProfileType();
	$profileFirstname = $profile->getFirstName();
	$profileLastname = $profile->getLastName();
	$profilePhone = $profile->getPhone();
	$profileImage = $profile->getImagePath();

	// if user makes edits, update in profile
	if($_POST['inputFirstname'] !== '') {
		$profileFirstname = $_POST['inputFirstname'];
		$profile->setFirstName($profileFirstname);
	}

	// if user makes edits, update in product
	if($_POST['inputLastname'] !== '') {
		$profileLastname = $_POST['inputLastname'];
		$profile->setLastName($profileLastname);
	}

	if($_POST['inputPhone'] !== '') {
		$profilePhone = $_POST['inputPhone'];
		$profile->setPhone($profilePhone);
	}
	// if user makes edits, update in product and upload image
	if(@isset($_FILES['inputImage']) === true) {
		$imageBasePath = '/var/www/html/farm-to-you/images/profile/';
		$imageExtension = checkInputImage($_FILES['inputImage']);
		$imageFileName = $imageBasePath . 'profile-' . $profileId . '.' . $imageExtension;
		$profile->setImagePath($imageFileName);
		move_uploaded_file($_FILES['inputImage']['tmp_name'], $imageFileName);
	}
		$profile->update($mysqli);

	echo "<p class=\"alert alert-success\">Profile for " . $profile->getFirstName() ." ". $profile->getLastName() . " updated!</p>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}
