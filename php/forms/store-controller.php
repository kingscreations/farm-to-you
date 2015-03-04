<?php

/**
 * This controller returns the products to show from a particular category
 *
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

// start session as the first statement
session_start();

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// model
require_once('../classes/category.php');
require_once('../classes/categoryproduct.php');
require_once('../classes/product.php');
require_once('../classes/store.php');

// get the credentials information from the server
require_once('/etc/apache2/capstone-mysql/encrypted-config.php');
$configFile = '/etc/apache2/capstone-mysql/farmtoyou.ini';

// the result is an
$results = [];

if(!@isset($_POST['category']) || !@isset($_POST['store'])) {

} else {
	$categoryName = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
	$storeId      = filter_var($_POST['store'], FILTER_SANITIZE_NUMBER_INT);
}

try {
	// connection
	$configArray = readConfig($configFile);
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	$products = Product::getAllProductsByStoreId($mysqli, $storeId);

	$categoryProducts = [];
	foreach($products as $product) {
		$resultCategoryProducts = CategoryProduct::getCategoryProductByProductId($mysqli, $product->getProductId());
		$categoryProducts = array_merge($categoryProducts, $resultCategoryProducts);
	}



	$mysqli->close();

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>