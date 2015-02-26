<?php
session_start();

$currentDir = dirname(__FILE__);
//require_once("../../dummy-session-single.php");
require_once ("../../root-path.php");

require_once("../classes/product.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../lib/utils.php");



// verify the form values have been submitted
if(@isset($_POST["editProductName"]) === false || @isset($_POST["editProductPrice"]) === false
	|| @isset($_POST["editProductDescription"]) === false || @isset($_POST["editProductWeight"]) === false || @isset($_POST["editStockLimit"]) === false)  {
	echo "<p class=\"alert alert-danger\">Form values not complete. Verify the form and try again.</p>";
}


try {
	//
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$product = Product::getProductByProductId($mysqli, $_SESSION['productId']);

	$productId = $product->getProductId();
	$productName = $product->getProductName();
	$productPrice = $product->getProductPrice();
	$productImagePath = $product->getImagePath();
	$productDescription = $product->getProductDescription();
	$productWeight = $product->getProductWeight();
	$productStockLimit = $product->getStockLimit();
	$productPriceType = $product->getProductPriceType();
	$storeId = $product->getStoreId();



	// if user makes edits, update in product
	if($_POST['editProductName'] !== '') {
		$productName = $_POST['editProductName'];
		$product->setProductName($productName);
	}

	// if user makes edits, update in product
	if($_POST['editProductPrice'] !== '') {
		$productPrice = $_POST['editProductPrice'];
		$product->setProductPrice($productPrice);
	}

	if($_POST['editProductWeight'] !== '') {
		$productWeight = $_POST['editProductWeight'];
		$product->setProductWeight($productWeight);
	}

	if($_POST['editStockLimit'] !== '') {
		$productStockLimit = $_POST['editStockLimit'];
		$product->setStockLimit($productStockLimit);
	}

	if($_POST['editProductPriceType'] !== '') {
		$productPriceType = $_POST['editProductPriceType'];
		$product->setProductPriceType($productPriceType);
	}

	// if user makes edits, update in product
	if ($_POST['editProductDescription'] !== ''){
		$productDescription = $_POST['editProductDescription'];
		$product->setProductDescription($productDescription);
		// else, if user leaves field empty, delete description and update store
	} else {
		$storeDescription = '';
		$store->setStoreDescription($storeDescription);
	}

	// if user makes edits, update in product and upload image
	if(@isset($_FILES['editProductImage']) === true) {
		$imageBasePath = '/var/www/html/farm-to-you/images/product/';
		$imageExtension = checkInputImage($_FILES['editProductImage']);
		$imageFileName = $imageBasePath . 'product-' . $productId . '.' . $imageExtension;
		$product->setImagePath($imageFileName);
		move_uploaded_file($_FILES['editProductImage']['tmp_name'], $imageFileName);
	}

	// update product in database
		$product->update($mysqli);



	echo "<p class=\"alert alert-success\">Product (id = " . $product->getProductId() . ") updated!</p>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}