<?php
//session_start();

$currentDir = dirname(__FILE__);
//require_once("../../dummy-session-single.php");
require_once ("../../root-path.php");

require_once("../classes/product.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../lib/utils.php");



// MAY HAVE TO CHANGE THIS TO NOT BE REQUIRED SINCE THIS IS JUST AN UPDATE. WILL TEST THIS ALONG WITH JS
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

$productId = 1;
$storeId = 1;

//	if(@isset($_POST["editProductImage"])) {
//		$product = new Product($productId, $storeId, $_POST["editProductImage"], $_POST["editProductName"], $_POST["editProductPrice"], $_POST["editProductDescription"], $_POST["editProductPriceType"], $_POST["editProductWeight"], $_POST["editStockLimit"]);
//	} else {
//		$product = new Product($productId, $storeId, null, $_POST["editProductName"], $_POST["editProductPrice"], $_POST["editProductDescription"], $_POST["editProductPriceType"], $_POST["editProductWeight"], $_POST["editStockLimit"]);
//	}

	if(@isset($_FILES['editProductImage'])) {
		$imageBasePath = '/var/www/html/farm-to-you/images/product/';
		$imageExtension = checkInputImage($_FILES['editProductImage']);
		$imageFileName = $imageBasePath . 'product-' . $productId . '.' . $imageExtension;
		$product = new Product($productId, $storeId, $imageBasePath, $_POST["editProductName"], $_POST["editProductPrice"], $_POST["editProductDescription"], $_POST["editProductPriceType"], $_POST["editProductWeight"], $_POST["editStockLimit"]);
		$product->update($mysqli);
		move_uploaded_file($_FILES['editProductImage']['tmp_name'], $imageFileName);
	} else {
		$product = new Product($productId, $storeId, null, $_POST["editProductName"], $_POST["editProductPrice"], $_POST["editProductDescription"], $_POST["editProductPriceType"], $_POST["editProductWeight"], $_POST["editStockLimit"]);
		$product->update($mysqli);
	}


	echo "<p class=\"alert alert-success\">Product (id = " . $product->getProductId() . ") updated!</p>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}