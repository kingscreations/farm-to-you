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
$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";

if(!@isset($_POST['productQuantity']) && !@isset($_POST['productWeight'])) {

}

try {

	// connection
	$configArray = readConfig($configFile);
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);



} catch(Exception $exception) {
	echo '<p class=\"alert alert-danger\">Exception: ' . $exception->getMessage() . '</p>';
}

var_dump($_POST);



?>