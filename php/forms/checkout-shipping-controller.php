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

try {
	mysqli_report(MYSQLI_REPORT_STRICT);

	// get the credentials information from the server
	$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
	$configArray = readConfig($configFile);

	// connection
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	// TODO: use that later to manage the locations with a select list AND then a possibility to add any location
	$location = new Location(null, "Grower's Market", 'US', 'NM', 'Albuquerque', 87102, 'Robinson Park');
	$location->insert($mysqli);

	$_SESSION['locations'] = array(
		array(
			'id' => $location->getLocationId()
		)
	);

	$mysqli->close();

	echo "<p class=\"alert alert-success\">Location (id = " . $location->getLocationId() . ") used successfully!</p>";

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>