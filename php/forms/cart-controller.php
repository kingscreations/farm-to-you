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
	for($i = 0; $i < count($_POST); $i++) {
		if(@isset($_POST['product'. ($i + 1) .'Quantity']) === false) {
			echo "<p class=\"alert alert-danger\">form values not complete. Verify the form and try again.</p>";
		}
	}

	// update the SESSION with the updated quantities from the cart
	for($i = 0; $i < count($_POST); $i++) {

		// filter the input
		$newQuantity = escapeshellcmd(filter_var($_POST['product' . ($i + 1) . 'Quantity']));

		$_SESSION['products'][$i]['quantity'] = $newQuantity;
	}

	$mysqli->close();

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>