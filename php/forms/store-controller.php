<?php

/**
 * This controller returns the product ids to hide from a particular category
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

// output array
$output = [];

if(!@isset($_POST['category']) || !@isset($_POST['store'])) {

	$output['error'] = 'Exception: problem with the inputs';
	json_encode($output);

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

	// test each of the products of the store to see if it matches the category
	$productIdsToHide = [];
	foreach($products as $product) {
		$categoryProducts = CategoryProduct::getCategoryProductByProductId($mysqli, $product->getProductId());

		foreach($categoryProducts as $categoryProduct) {
			$category = Category::getCategoryByCategoryId($mysqli, $categoryProduct->getCategoryId());

			// if NO match, add the current product to the product result set
			if($category->getCategoryName() !== $categoryName) {
				$productIdsToHide[] = $product->getProductId();
			}
		}
	}

	$mysqli->close();

} catch(Exception $exception) {

	$output['error'] = 'Exception: ' . $exception->getMessage();
	json_encode($output);
}

// result array will be return as a json object
$output = array(
	'products' => $productIdsToHide
);

echo json_encode($output);

?>