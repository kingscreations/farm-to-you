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
	exit();

} else {
	$categoryName = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
	$searchTerm   = filter_var($_POST['searchTerm'], FILTER_SANITIZE_STRING);

//	$output['category'] = $categoryName;
//	$output['searchTerm'] = $searchTerm;
//	echo json_encode($output);
//	exit();
}

try {
	// connection
	$configArray = readConfig($configFile);
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);


	// get the products from the input search term
	$products = Product::getProductByProductNameAndDescription($mysqli, $searchTerm);
	foreach($products as $product) {
		$productIds[] = $product->getProductId();
	}
	$output['productIds'] = $productIds;
//	echo json_encode($output);
//	exit();
	// test each of the products of the store to see if it matches the category
	$productsToShow = [];
	foreach($products as $index => $product) {
		$categoryProducts = CategoryProduct::getCategoryProductByProductId($mysqli, $product->getProductId());

//		if($index === 3) {
//			$output['productId'] = $product->getProductId();
//			$output['productName'] = $product->getProductName();
//			$output['categoryProducts'] = $categoryProducts;
//			if($categoryProducts !== null) {
//				$output['category1Id'] = $categoryProducts[0]->getCategoryId();
//				$output['category2Id'] = $categoryProducts[1]->getCategoryId();
//			}
//			echo json_encode($output);
//			exit();
//		}
		// just go directly to the next iteration since no category is available for this product
		if($categoryProducts === null) {
			continue;
		}

//		$output['category'] = $categoryName;
//		echo json_encode($output);
//		exit();
//		$productsToShow[] = $product;
//		continue;

		foreach($categoryProducts as $categoryProduct) {
			$category = Category::getCategoryByCategoryId($mysqli, $categoryProduct->getCategoryId());

			// if there is a match, then grab the product id inside $productIds
			if($category->getCategoryName() === $categoryName) {
				$productsToShow[] = $product;
				break;
			}
		}
	}

	// done with SQL queries
	$mysqli->close();

} catch(Exception $exception) {

	$output['error'] = 'Exception: ' . $exception->getMessage();
	echo json_encode($output);
	exit();
}


//$productIds = [];
//foreach($productsToShow as $productToShow) {
//	$productIds[] = $productToShow->getProductId();
//}
//$output['productIdsToShow'] = $productIds;
//
//$productsToHide = array_diff($products, $productsToShow);
//$productIds = [];
//foreach($productsToHide as $productToHide) {
//	$productIds[] = $productToHide->getProductId();
//}
//$output['productIdsToHide'] = $productIds;
//
//echo json_encode($output);
//exit();
////////

// finally, get the difference to only have the products we want to hide
$productsToHide = array_diff($products, $productsToShow);

// get the product ids
$productIds = [];
foreach($productsToHide as $productToHide) {
	$productIds[] = $productToHide->getProductId();
}

// result array will be return as a json object
$output = array(
	'products' => $productIds
);

echo json_encode($output);