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

	// store the array in the store locations session
	$_SESSION['storeLocations'] = [];
	foreach($_POST['storeLocation'] as $storeLocationMapping) {
		$_SESSION['storeLocations'][] = filter_var($storeLocationMapping, FILTER_SANITIZE_STRING);
	}
}

// redirect the client to the checkout page
header("Location: ../../checkout/");

?>