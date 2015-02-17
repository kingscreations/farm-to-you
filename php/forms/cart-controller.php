<?php

session_start();
//ob_start();

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

	/**
	 * submit form call
	 */


	foreach($_POST["productQuantity"] as $index => $productQuantity) {
		// update the SESSION with the updated quantities from the cart
		$newQuantity = escapeshellcmd(filter_var($productQuantity));
		$_SESSION['products'][$index]['quantity'] = $newQuantity;

	}

	$mysqli->close();
	header("Location: ../../checkout-shipping/");

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>