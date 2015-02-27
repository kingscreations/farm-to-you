<?php

/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

session_start();

// get the product ids from the session
$productIds = array_keys($_SESSION['products']);

// use cart form information
for($i = 0; $i < count($_POST["productQuantity"]); $i++) {
	// get the new product quantities from the $_POST global variable
	$newProductQuantity = filter_var($_POST['productQuantity'][$i], FILTER_SANITIZE_NUMBER_FLOAT);

	// get the product id from the $_SESSION global variable
	$productId = $productIds[$i];

	// update the SESSION with the up to date quantities
	$_SESSION['products'][$productId]['quantity'] = $newProductQuantity;
}

// redirect the client to the checkout shipping selection page
header("Location: ../../checkout-shipping/");


?>