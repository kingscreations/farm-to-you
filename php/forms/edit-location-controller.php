<?php
// dummy session
$currentDir = dirname(__FILE__);
require_once ("../../root-path.php");
require_once("../../dummy-session-single.php");

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// classes
require_once("../classes/location.php");
require_once("../classes/profile.php");
require_once("../classes/user.php");

try {

	// get the credentials information from the server and connect to the database
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	// throw exception if missing a required field
	if(!@isset($_POST["locationName"]) || !@isset($_POST["address1"]) ||
		!@isset($_POST["zipCode"]) || !@isset($_POST["city"]) || !@isset($_POST["state"])) {
		throw new Exception('missing a required field');
	}

	// grab location with id 1
	$location = Location::getLocationByLocationId($mysqli, 134);

	// create variables for location attributes
	$locationName = $location->getLocationName();
	$locationCountry = $location->getCountry();
	$locationState = $location->getState();
	$locationCity = $location->getCity();
	$locationZipCode = $location->getZipCode();
	$locationAddress1 = $location->getAddress1();
	$locationAddress2 = $location->getAddress2();

	// if user makes edits, update in location
	if($_POST['locationName'] !== '') {
		$locationName = $_POST['locationName'];
		$location->setLocationName($locationName);
	}

	// if user makes edits, update in location
	if ($_POST['country'] !== ''){
		$locationCountry = $_POST['country'];
		$location->setCountry($locationCountry);
	// else, if user leaves field empty, delete country and update store
	} else {
		$locationCountry = '';
		$location->setCountry($locationCountry);
	}

	// if user makes edits, update in location
	if($_POST['state'] !== '') {
		$locationState = $_POST['state'];
		$location->setState($locationState);
	}

	// if user makes edits, update in location
	if($_POST['city'] !== '') {
		$locationCity = $_POST['city'];
		$location->setCity($locationCity);
	}

	// if user makes edits, update in location
	if($_POST['zipCode'] !== '') {
		$locationZipCode = $_POST['zipCode'];
		$location->setZipCode($locationZipCode);
	}

	// if user makes edits, update in location
	if($_POST['address1'] !== '') {
		$locationAddress1 = $_POST['address1'];
		$location->setAddress1($locationAddress1);
	}

	// if user makes edits, update in location
	if ($_POST['address2'] !== ''){
		$locationAddress2 = $_POST['address2'];
		$location->setAddress2($locationAddress2);
	// else, if user leaves field empty, delete address 2 and update store
	} else {
		$locationAddress2 = '';
		$location->setAddress2($locationAddress2);
	}

	// update location in database
	$location->update($mysqli);

	echo "<p class=\"alert alert-success\">" . $location->getLocationName() . " updated!</p>";

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}
