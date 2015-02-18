<?php
session_start();

$currentDir = dirname(__FILE__);
require_once("../../dummy-session-single.php");
require_once ("../../root-path.php");

require_once("../classes/product.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// MAY HAVE TO CHANGE THIS TO NOT BE REQUIRED SINCE THIS IS JUST AN UPDATE. WILL TEST THIS ALONG WITH JS
// verify the form values have been submitted
if(@isset($_POST["editProductName"]) === false || @isset($_POST["editProductPrice"]) === false
	|| @isset($_POST["editProductDescription"]) === false || @isset($_POST["editProductWeight"]) === false || @isset($_POST["editStockLimit"]) === false)  {
	echo "<p class=\"alert alert-danger\">Form values not complete. Verify the form and try again.</p>";
}




//
//$_SESSION['product'] = array(
//	'id' 				=> $product->getProductId(),
//	'name'			=> $product->getProductName(),
//	'price'	=> $product->getProductPrice(),
//	'image'			=> $product->getImagePath(),
//	'description'		=> $product->getProductDescription(),
//	'weight' => $product->getProductWeight(),
//	'stock' => $product->getStockLimit(),
//	'priceType' => $product->getProductPriceType()
//);






try {
	//
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$profileId = $_SESSION['profile']['id'];
	$productId = $_SESSION['product']['id'];
	$productName = $_SESSION['product']['name'];
	$productPrice = $_SESSION['product']['price'];
	$productDescription = $_SESSION['product']['description'];
	$productPriceType = $_SESSION['product']['priceType'];
	$productWeight = $_SESSION['product']['weight'];
	$productImagePath = $_SESSION['product']['image'];
	$productStockLimit = $_SESSION['product']['stock'];


	if(@isset($_POST["editProductImage"])) {
		$product = new Product($productId, $profileId, $_POST["editProductImage"], $_POST["editProductName"], $_POST["editProductPrice"], $_POST["editProductDescription"], $_POST["editProductPriceType"], $_POST["editProductWeight"], $_POST["editStockLimit"]);
	} else {
		$product = new Product($productId, $profileId, null, $_POST["editProductName"], $_POST["editProductPrice"], $_POST["editProductDescription"], $_POST["editProductPriceType"], $_POST["editProductWeight"], $_POST["editStockLimit"]);
	}

	$product->update($mysqli);

	echo "<p class=\"alert alert-success\">Product (id = " . $product->getProductId() . ") posted!</p>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}