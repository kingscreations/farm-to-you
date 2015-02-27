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
	$productId = filter_var($_POST['product'], FILTER_SANITIZE_NUMBER_INT);
	$productId = intval($productId);
}

// check if at least productQuantity or productWeight exits
if(!@isset($_POST['productQuantity'])) {
	header('Location: '. $errorPath);
}

try {
	// connection
	$configArray = readConfig($configFile);
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	$product = Product::getProductByProductId($mysqli, $productId);

} catch(Exception $exception) {
	echo '<p class="alert alert-danger">Exception: ' . $exception->getMessage() . '</p>';
}

// create the products session if it does not already exist
if(!@isset($_SESSION['products'])) {
	$_SESSION['products'] = array();
}

$productQuantity = filter_var($_POST['productQuantity'], FILTER_SANITIZE_NUMBER_INT);

if(@isset($_SESSION['products']) && @isset($sessionProduct[$productId])) {
	$_SESSION['products'][$productId]['quantity'] = $sessionProduct['quantity'] + $productQuantity;
} else {
	$_SESSION['products'][$productId] = array(
		'quantity' => $productQuantity
	);
}

// return the number of product to the ajax call (update the cart icon)
echo count($_SESSION['products']);

?>