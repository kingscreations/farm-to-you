<?php
session_start();

$currentDir = dirname(__FILE__);
//require_once("../../dummy-session.php");
require_once ("../../root-path.php");

require_once("../classes/profile.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/user.php");
require_once("../classes/store.php");
require_once("../classes/product.php");
require_once("../lib/utils.php");


// verify the form values have been submitted
if(@isset($_POST["inputProductName"]) === false || @isset($_POST["inputProductPrice"]) === false
	|| @isset($_POST["inputProductDescription"]) === false || @isset($_POST["inputProductPriceType"]) === false || @isset($_POST["inputProductWeight"]) === false || @isset($_POST["inputStockLimit"]) === false)  {
	echo "<p class=\"alert alert-danger\">Form values not complete. Verify the form and try again.</p>";
}

$storeId = 145;

try {
	//insert into the database
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	//will insert the image if one is input, otherwise will place as null
	if(@isset($_FILES["inputProductImage"])) {
		$imageBasePath = '/var/www/html/farm-to-you/images/product/';
		$imageExtension = checkInputImage($_FILES['inputProductImage']);
		$product = new Product(null, $storeId, "", $_POST["inputProductName"], $_POST["inputProductPrice"], $_POST["inputProductDescription"], $_POST["inputProductPriceType"], $_POST["inputProductWeight"], $_POST["inputStockLimit"]);
		$product->insert($mysqli);
		$productId = $product->getProductId();
		$imageFileName = $imageBasePath . 'product-' . $productId . '.' . $imageExtension;
		$product->setImagePath($imageFileName);
		$product->update($mysqli);
		move_uploaded_file($_FILES['inputProductImage']['tmp_name'], $imageFileName);
	} else {
		$product = new Product(null, $storeId, "", $_POST["inputProductName"], $_POST["inputProductPrice"], $_POST["inputProductDescription"], $_POST["inputProductPriceType"], $_POST["inputProductWeight"], $_POST["inputStockLimit"]);
		$product->insert($mysqli);
		$productId = $product->getProductId();
	}

	//store the product into the session to be able to edit
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