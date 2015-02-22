<?php
//session_start();

$currentDir = dirname(__FILE__);
require_once ("../../root-path.php");
require_once("../../dummy-session-single.php");

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../lib/utils.php");
require_once("../classes/store.php");
require_once("../classes/location.php");
require_once("../classes/storelocation.php");
require_once("../classes/profile.php");
require_once("../classes/user.php");

try {

	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$store = Store::getStoreByStoreId($mysqli, 1);
	$storeId = $store->getStoreId();

	if(!@isset($_POST["locationName"]) || !@isset($_POST["address1"]) ||
		!@isset($_POST["zipCode"]) || !@isset($_POST["city"]) || !@isset($_POST["state"])) {
		throw new Exception('missing a required field');
	}

	$location = new Location(null, $_POST["locationName"], $_POST["country"], $_POST["state"], $_POST["city"],
		$_POST["zipCode"], $_POST["address1"], $_POST["address2"]);

	$location->insert($mysqli);
	$locationId = $location->getLocationId();
	$storeLocation = new StoreLocation($storeId, $locationId);
	$storeLocation->insert($mysqli);

	echo "<p class=\"alert alert-success\">" . $location->getLocationName() . " added!</p>";

	} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}