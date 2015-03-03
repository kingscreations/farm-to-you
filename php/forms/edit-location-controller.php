<?php
session_start();
// dummy session
$currentDir = dirname(__FILE__);
require_once ("../../root-path.php");

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// classes
require_once("../classes/location.php");
require_once("../classes/storelocation.php");
require_once("../classes/profile.php");
require_once("../classes/user.php");

$storeId = $_SESSION['storeId'];
$storeLocationOriginal = new StoreLocation($storeId, $_SESSION['locationId']);
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
	$locationOriginal = Location::getLocationByLocationId($mysqli, $_SESSION['locationId']);

	// create variables for location attributes
	$locationName = $locationOriginal->getLocationName();
	$locationCountry = $locationOriginal->getCountry();
	$locationState = $locationOriginal->getState();
	$locationCity = $locationOriginal->getCity();
	$locationZipCode = $locationOriginal->getZipCode();
	$locationAddress1 = $locationOriginal->getAddress1();
	$locationAddress2 = $locationOriginal->getAddress2();

	$location = new Location(null, $_POST['locationName'], $_POST['country'], $_POST['state'], $_POST['city'],
									 $_POST['zipCode'], $_POST['address1'], $_POST['address2']);
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

	$locationsAddress1 = Location::getLocationByAddress1($mysqli, $_POST["address1"]);
	if($locationsAddress1 !== null) {
		$locationFound = null;
		foreach($locationsAddress1 as $locationAddress1) {
			if ($locationAddress1->getZipCode() === $_POST["zipCode"]) {
				$locationFound = $locationAddress1;
				break;
			}
		}
	} else {
		$locationFound = null;
	}
	if($locationFound !== null) {
		$location = $locationFound;
		$locationId = $location->getLocationId();
		$storeLocation = new StoreLocation($storeId, $locationId);
		$storeLocationsDatabase = StoreLocation::getStoreLocationByStoreIdAndLocationId($mysqli, $storeId, $locationId);
		if($storeLocationsDatabase === null) {
			$storeLocation->insert($mysqli);
			$storeLocationOriginal->delete($mysqli);
		}
	} else {
		var_dump($location);
		$location->insert($mysqli);
		$locationId = $location->getLocationId();

		// create new StoreLocation
		$storeLocation = new StoreLocation($storeId, $locationId);

		// insert storeLocation
		$storeLocation->insert($mysqli);
		$storeLocationOriginal->delete($mysqli);
	}
//
//	// update location in database
//	$location->update($mysqli);

	echo "<p class=\"alert alert-success\">" . $location->getLocationName() . " updated!</p>";

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}
