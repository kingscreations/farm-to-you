<?php

// category search controller

session_start();

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// model
require_once("../classes/product.php");
require_once("../classes/category.php");
require_once("../classes/categoryproduct.php");

$configFile = '/etc/apache2/capstone-mysql/farmtoyou.ini';



// output array
$output = [];

if(!@isset($_POST['category']) || !@isset($_POST['searchTerm'])) {

	$output['error'] = 'Exception: problem with the inputs';
	echo json_encode($output);

} else {
	$categoryName = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
	$searchTerm     = filter_var($_POST['searchTerm'], FILTER_SANITIZE_STRING);
}

try {
	// connection
	$configArray = readConfig($configFile);
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);


	$products = Product::getProductByProductNameAndDescription($mysqli, $_POST['searchTerm']);

// test each of the products of the store to see if it matches the category
$productsToShow = [];
foreach($products as $product) {

	$categoryProducts = CategoryProduct::getCategoryProductByProductId($mysqli, $product->getProductId());

	if($categoryProducts === null){
		continue;
	}

	foreach($categoryProducts as $categoryProduct) {
		$category = Category::getCategoryByCategoryId($mysqli, $categoryProduct->getCategoryId());

		$categoryName = $category->getCategoryName();
		// if there is a match, then grab the product id inside $productIds
		if($category->getCategoryName() === $categoryName) {
			$productsToShow[] = $product;
			break;
		}
	}
}


// finally, get the difference to only have the products we want to hide
$productsToHide = array_diff($products, $productsToShow);


// get the product ids
$productIds = [];
foreach($productsToHide as $productToHide) {
	$productIds[] = $productToHide->getProductId();
}
	$mysqli->close();

} catch(Exception $exception) {

	$output['error'] = 'Exception: ' . $exception->getMessage();
	json_encode($output);
}

// result array will be return as a json object
$output = array(
	'products' => $productIds
);

echo json_encode($output);
