<?php

$currentDir = dirname(__FILE__);
require_once ("../../root-path.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/location.php");
require_once("../classes/profile.php");
require_once("../classes/user.php");
require_once("../lib/utils.php");

try {

	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$location = Location::getLocationByLocationId($mysqli, 3);

	$locationName = $location->getLocationName();
	$locationCountry = $location->getCountry();
	$locationState = $location->getState();
	$locationCity = $location->getCity();
	$locationZipCode = $location->getZipCode();
	$locationAddress1 = $location->getAddress1();
	$locationAddress2 = $location->getAddress2();

	if($_POST['locationName'] !== '') {
		$locationName = $_POST['locationName'];
		$location->setLocationName($locationName);
	}

	if ($_POST['country'] !== ''){
		$locationCountry = $_POST['country'];
		$location->setCountry($locationCountry);
	} else {
		$locationCountry = '';
		$location->setCountry($locationCountry);
	}

	if($_POST['state'] !== '') {
		$locationState = $_POST['state'];
		$location->setState($locationState);
	}

	if($_POST['city'] !== '') {
		$locationCity = $_POST['city'];
		$location->setCity($locationCity);
	}

	if($_POST['zipCode'] !== '') {
		$locationZipCode = $_POST['zipCode'];
		$location->setZipCode($locationZipCode);
	}

	if($_POST['address1'] !== '') {
		$locationAddress1 = $_POST['address1'];
		$location->setAddress1($locationAddress1);
	}

	if ($_POST['address2'] !== ''){
		$locationAddress2 = $_POST['address2'];
		$location->setAddress2($locationAddress2);
	} else {
		$locationAddress2 = '';
		$location->setAddress2($locationAddress2);
	}

	$location->update($mysqli);

	echo "<p class=\"alert alert-success\">" . $location->getLocationName() . " updated!</p>";

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}
