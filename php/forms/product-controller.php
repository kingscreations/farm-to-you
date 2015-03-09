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

	// get the stock limit
	$stockLimit = $product->getStockLimit();

	// a user can choose only between 1 to 99 elements if there is not stock limit set by the merchant
	// TODO stock limit should probably be a required field
	$maxQuantity = 99;

	if($stockLimit === null) {
		$stockLimit = $maxQuantity;
	}

} catch(Exception $exception) {
	echo '<p class="alert alert-danger">Exception: ' . $exception->getMessage() . '</p>';
}

// create the products session if it does not already exist
if(!@isset($_SESSION['products'])) {
	$_SESSION['products'] = array();
}

// malicious and incompetent users
$newProductQuantityToAdd = intval(filter_var($_POST['productQuantity'], FILTER_SANITIZE_NUMBER_INT));

if($newProductQuantityToAdd === 0) {
	$output = [];
	$output['error'] = 'Exception: newProductQuantityToAdd cannot be equal to 0';
}

// keep the old quantity to calculate how many products have been added
// we initialize it at 0 if we add a new product to the cart (not just a quantity update)
$oldProductQuantity = 0;

$simpleQuantityUpdate = @isset($_SESSION['products'][$productId])
	&& @isset($_SESSION['products'][$productId]['quantity'])
	&& @isset($_SESSION['products'][$productId]['availableQuantity']);


// update the product quantity
if($simpleQuantityUpdate === true) {

	$availableQuantity = $_SESSION['products'][$productId]['availableQuantity'];

	// save the old product quantity and get the new quantity
	$oldProductQuantity = $_SESSION['products'][$productId]['quantity'];

	// $newProductQuantity is the new up to date quantity in our products session array
	$newProductQuantity = $oldProductQuantity + $newProductQuantityToAdd;


	// OR new product added to the cart
} else {

	$availableQuantity = $stockLimit;

	// first time the user clicks on the add to cart button for this product
	$newProductQuantity = $newProductQuantityToAdd;
}

// check if the new quantity is not greater than the stock limit decided by the merchant
if($newProductQuantity > $stockLimit) {

	// fix the quantity if there is more than the stock limit
	$newProductQuantity = $stockLimit;
	$availableQuantity = 0;

	// setup the failure message for the end user
	$numberProductsAdded = $newProductQuantity - $oldProductQuantity;

	if($numberProductsAdded > 0) {
		$text = ($numberProductsAdded === 1) ? ' has been added to your cart.' : ' have been added to your cart.';
		$message = '<p class="alert alert-danger">Only ' . $numberProductsAdded . ' "' . $product->getProductName() . '"' .
			$text . '<br/> This product is out of stock now.</p>';

	} else {
		$message = '<p class="alert alert-danger">No "' . $product->getProductName() . '" have been added to your cart.<br/>
		This product is out of stock now.</p>';
	}

	// new product or new quantity added successfully
} else {

	// setup the success message for the end user
	$numberProductsAdded = $newProductQuantityToAdd;
	$availableQuantity = $availableQuantity - $numberProductsAdded;

	$text = ($newProductQuantityToAdd === 1) ? ' has been added.' : ' have been added to your cart.';
	$message = '<p class="alert alert-success">' . $newProductQuantityToAdd . ' "' . $product->getProductName() . '"' .
		$text . '</p>';
}

// finally update the products session array
$_SESSION['products'][$productId]['quantity'] = $newProductQuantity;
$_SESSION['products'][$productId]['availableQuantity'] = $availableQuantity;

// return the number of product to js (update the cart icon)
$output = array(

	// the cart count is related only to the number of different products
	'cartCount'         => count($_SESSION['products']),

	// communicate the available quantity to js to update the select dropdown
	'availableQuantity' => $_SESSION['products'][$productId]['availableQuantity'],

	'message'           => $message
);

// send the data to js
echo json_encode($output);

?>