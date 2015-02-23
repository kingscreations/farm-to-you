<?php

// dummy session
$currentDir = dirname(__FILE__);
require_once ("../../root-path.php");
require_once("../../dummy-session-single.php");

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

	$profileId = $_SESSION['profile']['id'];

	if(!@isset($_POST["locationName"]) || !@isset($_POST["address1"]) ||
		!@isset($_POST["zipCode"]) || !@isset($_POST["city"]) || !@isset($_POST["state"]) || !@isset($_POST["storeName"])) {
		throw new Exception('missing a required field');
	}


	$location = new Location(null, $_POST["locationName"], $_POST["country"], $_POST["state"], $_POST["city"],
		$_POST["zipCode"], $_POST["address1"], $_POST["address2"]);
	$store = new Store(null, $profileId, $_POST["storeName"], null, null, $_POST["storeDescription"]);

	if(@isset($_FILES['inputImage'])) {
		$imageBasePath = '/var/www/html/farm-to-you/images/store/';
		$imageExtension = checkInputImage($_FILES['inputImage']);
		$store->insert($mysqli);
		$storeId = $store->getStoreId();
		$imageFileName = $imageBasePath . 'store-' . $storeId . '.' . $imageExtension;
		$store->setImagePath($imageFileName);
		$store->update($mysqli);
		move_uploaded_file($_FILES['inputImage']['tmp_name'], $imageFileName);
	} else {
		$store->setImagePath(null);
		$store->insert($mysqli);
		$storeId = $store->getStoreId();
	}


	$location->insert($mysqli);
	$locationId = $location->getLocationId();
	$storeLocation = new StoreLocation($storeId, $locationId);
	$storeLocation->insert($mysqli);

//	$storeNames = Store::getAllStoresByProfileId($mysqli, $store->getProfileId());

//	$_SESSION['store'] = array(
//		'id' 				=> $store->getStoreId(),
//		'name'			=> $store->getStoreName(),
//		'description'	=> $store->getStoreDescription(),
//		'image'			=> $store->getImagePath(),
//		'creation'		=> $store->getCreationDate()
//	);

	echo "<p class=\"alert alert-success\">" . $store->getStoreName() . " added!</p><br>
			<p class=\"alert alert-success\">" . $location->getLocationName() . " added!</p>";


	} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}