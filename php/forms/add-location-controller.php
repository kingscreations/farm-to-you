<?php
session_start();
// dummy session
$currentDir = dirname(__FILE__);
require_once ("../../root-path.php");

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// image input processing
require_once("../lib/utils.php");

// classes
require_once("../classes/store.php");
require_once("../classes/location.php");
require_once("../classes/storelocation.php");
require_once("../classes/profile.php");
require_once("../classes/user.php");

try {

	// get the credentials information from the server and connect to the database
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	// grab store with id 1
	$store = Store::getStoreByStoreId($mysqli, $_SESSION["storeId"]);

	// create variable for store id
	$storeId = $store->getStoreId();

	// throw exception if missing a required field
	if(!@isset($_POST["locationName"]) || !@isset($_POST["address1"]) ||
		!@isset($_POST["zipCode"]) || !@isset($_POST["city"]) || !@isset($_POST["state"])) {
		throw new Exception('missing a required field');
	}

	// create new Location with form input

	$location = new Location(null, $_POST["locationName"], $_POST["country"], $_POST["state"], $_POST["city"],
		$_POST["zipCode"], $_POST["address1"], $_POST["address2"]);
	if($location->equals)

//	$locationsAddress1 = Location::getLocationByAddress1($mysqli, $_POST["address1"]);
//	if($locationsAddress1 !== null) {
//		$locationZipCode = Location::getLocationByZipCode($locationsAddress1, $_POST["zipCode"]);
//		if($locationZipCode !== null){
//			$location = $locationZipCode;
//		} else {
//			$location = new Location(null, $_POST["locationName"], $_POST["country"], $_POST["state"], $_POST["city"],
//				$_POST["zipCode"], $_POST["address1"], $_POST["address2"]);
//			$location->insert($mysqli);
//		}
//	} else {
//		$location = new Location(null, $_POST["locationName"], $_POST["country"], $_POST["state"], $_POST["city"],
//			$_POST["zipCode"], $_POST["address1"], $_POST["address2"]);
//		$location->insert($mysqli);
//	}

	// create variable for location id
	$locationId = $location->getLocationId();

	// create new StoreLocation
	$storeLocation = new StoreLocation($storeId, $locationId);

	// insert storeLocation
	$storeLocation->insert($mysqli);

	echo "<p class=\"alert alert-success\">" . $location->getLocationName() . " added!</p>";

	} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}