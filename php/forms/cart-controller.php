<?php

/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

session_start();

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

require_once("../classes/orderproduct.php");
require_once("../classes/product.php");
require_once("../classes/order.php");

try {
	mysqli_report(MYSQLI_REPORT_STRICT);

	// get the credentials information from the server
	$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
	$configArray = readConfig($configFile);

	// connection
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	// get the product ids from the session
	$productIds = array_keys($_SESSION['products']);

	// use cart form information
	for($i = 0; $i < count($_POST["productQuantity"]); $i++) {
		// get the new product quantities from the $_POST global variable
		$newProductQuantity = filter_var($_POST['productQuantity'][$i], FILTER_SANITIZE_NUMBER_FLOAT);

		// get the product id from the $_SESSION global variable
		$productId          = $productIds[$i];

		// update the SESSION with the up to date quantities
		$_SESSION['products'][$productId]['quantity'] = $newProductQuantity;
	}
	$mysqli->close();

	// redirect the client to the checkout shipping selection page
	header("Location: ../../checkout-shipping/");

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>