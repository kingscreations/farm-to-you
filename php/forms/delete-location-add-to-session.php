<?php

require_once("../../php/classes/location.php");
require_once("../../php/classes/storelocation.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

if(@isset($_POST["locationId"]) === true) {
	$_POST["locationId"] = filter_input(INPUT_POST, "locationId", FILTER_VALIDATE_INT);
	if($_POST["locationId"] !== false) {
		$locationId = $_POST["locationId"];
		try {

			// get the credentials information from the server and connect to the database
			mysqli_report(MYSQLI_REPORT_STRICT);
			$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
			$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

			$storeLocations = StoreLocation::getAllStoreLocationsByLocationId($mysqli,$locationId);
			foreach($storeLocations as $storeLocation) {
				$storeLocation->delete($mysqli);
			}
			$location = Location::getLocationByLocationId($mysqli, $locationId);
			$location->delete($mysqli);

		} catch (Exception $exception) {
			echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
		}
	}
}
?>