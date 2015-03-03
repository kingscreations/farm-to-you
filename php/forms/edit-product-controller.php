<?php
session_start();

$currentDir = dirname(__FILE__);
//require_once("../../dummy-session-single.php");
require_once ("../../root-path.php");

require_once("../classes/product.php");
require_once("../classes/category.php");
require_once("../classes/categoryproduct.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../lib/utils.php");

//var_dump($_SESSION);
//var_dump($_POST);

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

	$category1 = Category::getCategoryByCategoryId($mysqli, $_SESSION["categoryId1"]);
	$category2 = Category::getCategoryByCategoryId($mysqli, $_SESSION["categoryId2"]);
	$category3 = Category::getCategoryByCategoryId($mysqli, $_SESSION["categoryId3"]);
	$category4 = Category::getCategoryByCategoryId($mysqli, $_SESSION["categoryId4"]);

	$categoryProduct1 = null;
	$categoryProduct2 = null;
	$categoryProduct3 = null;
	$categoryProduct4 = null;

	if($category1 !== null) {
		$categoryName1 = $category1->getCategoryName();
	} else {
		$categoryName1 = "";
	}
	if($category2 !== null) {
		$categoryName2 = $category2->getCategoryName();
	} else {
		$categoryName2 = "";
	}
	if($category3 !== null) {
		$categoryName3 = $category3->getCategoryName();
	} else {
		$categoryName3 = "";
	}
	if($category4 !== null) {
		$categoryName4 = $category4->getCategoryName();
	} else {
		$categoryName4 = "";
	}

	if($_SESSION["categoryId1"] !== null) {
		$categoryProduct1 = CategoryProduct::getCategoryProductByCategoryIdAndProductId($mysqli, $_SESSION["categoryId1"], $productId);
	}
	if($_SESSION["categoryId2"] !== null) {
		$categoryProduct2 = CategoryProduct::getCategoryProductByCategoryIdAndProductId($mysqli, $_SESSION["categoryId2"], $productId);
	}
	if($_SESSION["categoryId3"] !== null) {
		$categoryProduct3 = CategoryProduct::getCategoryProductByCategoryIdAndProductId($mysqli, $_SESSION["categoryId3"], $productId);
	}
	if($_SESSION["categoryId4"] !== null) {
		$categoryProduct4 = CategoryProduct::getCategoryProductByCategoryIdAndProductId($mysqli, $_SESSION["categoryId4"], $productId);
	}

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
		$productDescription = '';
		$product->setProductDescription($productDescription);
	}

	// if user makes edits, update in product and upload image
	if(@isset($_FILES['editProductImage']) === true) {
		$imageBasePath = '/var/www/html/farm-to-you/images/product/';
		$imageExtension = checkInputImage($_FILES['editProductImage']);
		$imageFileName = $imageBasePath . 'product-' . $productId . '.' . $imageExtension;
		$product->setImagePath($imageFileName);
		move_uploaded_file($_FILES['editProductImage']['tmp_name'], $imageFileName);
	}
	if (empty($_POST["addTags1"])=== false){
		$categoryNameDatabase = Category::getCategoryByCategoryName($mysqli, $_POST["addTags1"]);
		if($categoryNameDatabase !== null) {
			$category1 = $categoryNameDatabase;
			$categoryId1 = $category1->getCategoryId();
			$categoryProductNew = new CategoryProduct($categoryId1, $productId);
			$categoryProductNewDatabase = CategoryProduct::getCategoryProductByCategoryIdAndProductId($mysqli, $categoryId1, $productId);
			var_dump($categoryProductNew);
			var_dump($categoryProductNewDatabase);
			var_dump($categoryProductNew == $categoryProductNewDatabase);
			if($categoryProductNew != $categoryProductNewDatabase) {
				$categoryProductNew->insert($mysqli);
			}
		} else {
			$category1 = new Category(null, $_POST["addTags1"]);
			$category1->insert($mysqli);
			$categoryId1 = $category1->getCategoryId();
			$categoryProduct1 = new CategoryProduct($categoryId1, $productId);
			$categoryProduct1->insert($mysqli);
		}
	} else {
		if($categoryProduct1 !== null) {
			$categoryProduct1->delete($mysqli);
		}
	}

//	var_dump($categoryProduct1);
//	var_dump($category1);


	if (empty($_POST["addTags2"])=== false){
		$categoryNameDatabase = Category::getCategoryByCategoryName($mysqli, $_POST["addTags2"]);
		if($categoryNameDatabase !== null) {
			$category2 = $categoryNameDatabase;
			$categoryId1 = $category2->getCategoryId();
			$categoryProductNew = new CategoryProduct($categoryId1, $productId);
			$categoryProductNewDatabase = CategoryProduct::getCategoryProductByCategoryIdAndProductId($mysqli, $categoryId1, $productId);
			if($categoryProductNew != $categoryProductNewDatabase) {
				$categoryProductNew->insert($mysqli);
			}
		} else {
			$category2 = new Category(null, $_POST["addTags2"]);
			$category2->insert($mysqli);
			$categoryId1 = $category2->getCategoryId();
			$categoryProduct2 = new CategoryProduct($categoryId1, $productId);
			$categoryProduct2->insert($mysqli);
		}
	} else {
		if($categoryProduct2 !== null) {
			$categoryProduct2->delete($mysqli);
		}
	}

	if (empty($_POST["addTags3"])=== false){
		$categoryNameDatabase = Category::getCategoryByCategoryName($mysqli, $_POST["addTags3"]);
		if($categoryNameDatabase !== null) {
			$category3 = $categoryNameDatabase;
			$categoryId1 = $category3->getCategoryId();
			$categoryProductNew = new CategoryProduct($categoryId1, $productId);
			$categoryProductNewDatabase = CategoryProduct::getCategoryProductByCategoryIdAndProductId($mysqli, $categoryId1, $productId);
			if($categoryProductNew != $categoryProductNewDatabase) {
				$categoryProductNew->insert($mysqli);
			}
		} else {
			$category3 = new Category(null, $_POST["addTags3"]);
			$category3->insert($mysqli);
			$categoryId1 = $category3->getCategoryId();
			$categoryProduct3 = new CategoryProduct($categoryId1, $productId);
			$categoryProduct3->insert($mysqli);
		}
	} else {
		if($categoryProduct3 !== null) {
			$categoryProduct3->delete($mysqli);
		}
	}

	if (empty($_POST["addTags4"])=== false){
		$categoryNameDatabase = Category::getCategoryByCategoryName($mysqli, $_POST["addTags4"]);
		if($categoryNameDatabase !== null) {
			$category4 = $categoryNameDatabase;
			$categoryId1 = $category4->getCategoryId();
			$categoryProductNew = new CategoryProduct($categoryId1, $productId);
			$categoryProductNewDatabase = CategoryProduct::getCategoryProductByCategoryIdAndProductId($mysqli, $categoryId1, $productId);
			if($categoryProductNew != $categoryProductNewDatabase) {
				$categoryProductNew->insert($mysqli);
			}
		} else {
			$category4 = new Category(null, $_POST["addTags4"]);
			$category4->insert($mysqli);
			$categoryId1 = $category4->getCategoryId();
			$categoryProduct4 = new CategoryProduct($categoryId1, $productId);
			$categoryProduct4->insert($mysqli);
		}
	} else {
		if($categoryProduct4 !== null) {
			$categoryProduct4->delete($mysqli);
		}
	}

	var_dump($categoryProduct1);
	var_dump($categoryProduct2);
	var_dump($categoryProduct3);
	var_dump($categoryProduct4);

	// update product in database
		$product->update($mysqli);



	echo "<p class=\"alert alert-success\">Product (id = " . $product->getProductId() . ") updated!</p>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}