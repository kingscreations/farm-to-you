<?php
session_start();

$currentDir = dirname(__FILE__);
require_once("../../dummy-session.php");
require_once ("../../root-path.php");

require_once("../classes/profile.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/user.php");
require_once("../classes/product.php");


// verify the form values have been submitted
if(@isset($_POST["inputProductName"]) === false || @isset($_POST["inputProductPrice"]) === false
	|| @isset($_POST["inputProductDescription"]) === false || @isset($_POST["inputProductPriceType"]) === false || @isset($_POST["inputProductWeight"]) === false || @isset($_POST["inputStockLimit"]) === false)  {
	echo "<p class=\"alert alert-danger\">Form values not complete. Verify the form and try again.</p>";
}

$profileId = $_SESSION['profile']['id'];

try {
	//
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);


	if(@isset($_POST["inputProductImage"])) {
		$product = new Product(null, $profileId, $_POST["inputProductImage"], $_POST["inputProductName"], $_POST["inputProductPrice"], $_POST["inputProductDescription"], $_POST["inputProductPriceType"], $_POST["inputProductWeight"], $_POST["inputStockLimit"]);
	} else {
		$product = new Product(null, $profileId, null, $_POST["inputProductName"], $_POST["inputProductPrice"], $_POST["inputProductDescription"], $_POST["inputProductPriceType"], $_POST["inputProductWeight"], $_POST["inputStockLimit"]);
	}

	$product->insert($mysqli);

	$_SESSION['product'] = array(
		'id' 				=> $product->getProductId(),
		'name'			=> $product->getProductName(),
		'price'	=> $product->getProductPrice(),
		'image'			=> $product->getImagePath(),
		'description'		=> $product->getProductDescription(),
		'weight' => $product->getProductWeight(),
		'stock' => $product->getStockLimit(),
		'priceType' => $product->getProductPriceType()
	);

	echo "<p class=\"alert alert-success\">Product (id = " . $product->getProductId() . ") posted!</p>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}