<?php

/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

session_start();

// model
require_once("../classes/product.php");

// connection configuration
mysqli_report(MYSQLI_REPORT_STRICT);

// get the credentials information from the server
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";

$errorPath = '../lib/404.php';

// check if the product id is a decimal and then show error or filter the input
if(!@isset($_POST['product']) && ctype_digit($_POST['product'])) {
	header('Location: '. $errorPath);
} else {
	$productId = filter_var($_POST, FILTER_SANITIZE_NUMBER_INT);
}

// check if at least productQuantity or productWeight exits
if(!@isset($_POST['productQuantity']) && !@isset($_POST['productWeight'])) {
	header('Location: '. $errorPath);
}

//try {
//
//	// connection
//	$configArray = readConfig($configFile);
//	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
//		$configArray["database"]);
//
//	// connection???
//
//} catch(Exception $exception) {
//	echo '<p class=\"alert alert-danger\">Exception: ' . $exception->getMessage() . '</p>';
//}

// create the products session if it does not already exist
if(!@isset($_SESSION['products'])) {
	$_SESSION['products'] = array();
}

//
if(@isset($_POST['productQuantity'])) {
	$productQuantity = filter_var($_POST['productQuantity'], FILTER_SANITIZE_NUMBER_INT);


}

if(@isset($_POST['productWeight'])) {
	$productQuantity = filter_var($_POST['productWeight'], FILTER_SANITIZE_NUMBER_FLOAT);
}

//$_SESSION['products'] = array(
//	$product1->getProductId() => array(
//		'quantity' => 7,
//		'locations' => array(
//			$location->getLocationId()
//		)
//	),
//	$product2->getProductId() => array(
//		'quantity' => 5,
//		'locations' => array(
//			$location->getLocationId()
//		)
//	)
//);


?>