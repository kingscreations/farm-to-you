<?php
session_start();


$currentDir = dirname(__FILE__);
require_once("../../dummy-session-single.php");
require_once ("../../root-path.php");

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/store.php");
require_once("../classes/location.php");
require_once("../classes/storelocation.php");
require_once("../classes/profile.php");
require_once("../classes/user.php");
require_once("../lib/utils.php");


// verify the form values have been submitted
//if(@isset($_POST["storeName"]) === false) {
//	echo "<p class=\"alert alert-danger\">Form values not complete. Verify the form and try again.</p>";
//}

try {

	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$store = new Store(null, $profileId, $_POST["storeName"], '', null, $_POST["storeDescription"]);

	$store->insert($mysqli);
	$storeId = $store->getStoreId();



	$location->insert($mysqli);
	$locationId = $location->getLocationId();
	$storeLocation = new StoreLocation($storeId, $locationId);
	$storeLocation->insert($mysqli);

//	$storeNames = Store::getAllStoresByProfileId($mysqli, $store->getProfileId());

	$_SESSION['store'] = array(
		'id' 				=> $store->getStoreId(),
		'name'			=> $store->getStoreName(),
		'description'	=> $store->getStoreDescription(),
		'image'			=> $store->getImagePath(),
		'creation'		=> $store->getCreationDate()
	);

	echo "<p class=\"alert alert-success\">" . $store->getStoreName() . " added!</p><br><p class=\"alert alert-success\">" . $location->getLocationName() . " added!</p>";


} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}
?>