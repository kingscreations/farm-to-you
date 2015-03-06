<?php

/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

// start session as the first statement
session_start();

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// model
require_once("../classes/location.php");

if(!@isset($_POST['storeLocation']) || !is_array($_POST['storeLocation'])) {
	header('Location: ../lib/404.php');
} else {
	var_dump($_POST['storeLocation']);
	$storeLocationsMapping = filter_var($_POST['storeLocation'], FILTER_SANITIZE_STRING);
var_dump($storeLocationsMapping);
	exit();
	$storesId    = [];
	$locationsId = [];
	foreach($storeLocationsMapping as $storeLocationMapping) {
		$storeLocationMappingExploded = explode('|', $storeLocationMapping);
		var_dump($storeLocationMappingExploded);
	}
}

try {
	mysqli_report(MYSQLI_REPORT_STRICT);

	// get the credentials information from the server
	$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
	$configArray = readConfig($configFile);

	// connection
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	// TODO: use that later to manage the locations with a select list AND then a possibility to add any location
	$location = new Location(null, "Grower's Market", 'US', 'NM', 'Albuquerque', '87108', 'Robinson Park');
	$location->insert($mysqli);

	$mysqli->close();

	// store the location for the next step
	$_SESSION['order-location'] = $location->getLocationId();

	// redirect the client to the checkout page
//	header("Location: ../../checkout/");

	echo "<p class=\"alert alert-success\">Location (id = " . $location->getLocationId() . ") used successfully!</p>";

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>