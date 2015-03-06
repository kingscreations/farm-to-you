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

	// get profile id from session
	$profileId = $_SESSION['profileId'];
//	$profileId = 1;

	// throw exception if missing a required field
	if(!@isset($_POST["locationName"]) || !@isset($_POST["address1"]) ||
		!@isset($_POST["zipCode"]) || !@isset($_POST["city"]) || !@isset($_POST["state"]) || !@isset($_POST["storeName"])) {
		throw new Exception('missing a required field');
	}

	// create new Location and Store with form input
	$location = new Location(null, $_POST["locationName"], $_POST["country"], $_POST["state"], $_POST["city"],
		$_POST["zipCode"], $_POST["address1"], $_POST["address2"]);
	$store = new Store(null, $profileId, $_POST["storeName"], null, null, $_POST["storeDescription"]);

	// if user provides input image, sanitize, add base path, insert store, grab store id, update image path,
	// update store, and upload image
	if(@isset($_FILES['inputImage'])) {
		$imageBasePath = '/var/www/html/farm-to-you/images/store/';
		$imageExtension = checkInputImage($_FILES['inputImage']);
		$store->insert($mysqli);
		$storeId = $store->getStoreId();
		$imageFileName = $imageBasePath . 'store-' . $storeId . '.' . $imageExtension;
		$store->setImagePath($imageFileName);
		$store->update($mysqli);
		move_uploaded_file($_FILES['inputImage']['tmp_name'], $imageFileName);
	// else, set to null, insert store and grab store id
	} else {
		$store->setImagePath(null);
		$store->insert($mysqli);
		$storeId = $store->getStoreId();
	}

	// insert location
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
		$storeLocation->insert($mysqli);

	} else {
		$location = new Location(null, $_POST["locationName"], $_POST["country"], $_POST["state"], $_POST["city"],
			$_POST["zipCode"], $_POST["address1"], $_POST["address2"]);
		$location->insert($mysqli);
		$locationId = $location->getLocationId();

		// create new StoreLocation
		$storeLocation = new StoreLocation($storeId, $locationId);

		// insert storeLocation
		$storeLocation->insert($mysqli);
	}

	echo "<p class=\"alert alert-success\">" . $store->getStoreName() . " added!</p><br>
			<p class=\"alert alert-success\">" . $location->getLocationName() . " added!</p>";

	} catch(Exception $exception) {
	
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}