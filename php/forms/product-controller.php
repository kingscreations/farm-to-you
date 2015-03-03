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

$maxQuantity = 99; // TODO not sure we need a max quantity
$stockLimit  = $product->getStockLimit();

if($stockLimit === null) {
	$stockLimit = $maxQuantity;
}

// keep the old quantity to calculate how many products have been added
$old_quantity = 0;

if(@isset($_SESSION['products'][$productId])) {

	$old_quantity = $_SESSION['products'][$productId]['quantity'];

	$_SESSION['products'][$productId]['quantity'] = $_SESSION['products'][$productId]['quantity'] + $productQuantity;
} else {
	$_SESSION['products'][$productId] = array(
		'quantity' => $productQuantity
	);
}

if($_SESSION['products'][$productId]['quantity'] >= $stockLimit) {
	$numberProductsAdded = $stockLimit - $old_quantity;
	$_SESSION['products'][$productId]['quantity'] = $stockLimit;
	$message = '<p class="alert alert-danger">' . $numberProductsAdded . ' ' . $product->getProductName() . ' have been added to your cart.<br/> This product is out of stock now.</p>';
} else {
	$message = '<p class="alert alert-success">' . $productQuantity . ' ' . $product->getProductName() . ' has been added to your cart.</p>';
}
// return the number of product to the ajax call (update the cart icon)
$output = array(
	'cartCount' => count($_SESSION['products']),
	'message' => $message
);

echo json_encode($output);

?>