<?php
session_start();

$currentDir = dirname(__FILE__);
//require_once("../../dummy-session.php");
require_once ("../../root-path.php");

require_once("../classes/profile.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/user.php");
require_once("../classes/store.php");
require_once("../classes/category.php");
require_once("../classes/categoryproduct.php");
require_once("../classes/product.php");
require_once("../lib/utils.php");


// verify the form values have been submitted
if(@isset($_POST["inputProductName"]) === false || @isset($_POST["inputProductPrice"]) === false
	|| @isset($_POST["inputProductDescription"]) === false || @isset($_POST["inputProductPriceType"]) === false || @isset($_POST["inputProductWeight"]) === false || @isset($_POST["inputStockLimit"]) === false)  {
	echo "<p class=\"alert alert-danger\">Form values not complete. Verify the form and try again.</p>";
}

$storeId = $_SESSION["storeId"];

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

		if(empty($_POST["addTags1"])=== false) {
			$categoryName1 = Category::getCategoryByCategoryName($mysqli, $_POST["addTags1"]);
			if($categoryName1 !== null) {
				$category1 = $categoryName1;
				$categoryId1 = $category1->getCategoryId();
				$categoryProduct1 = new CategoryProduct($categoryId1, $productId);
				$categoryProduct1->insert($mysqli);
			} else {
				$category1 = new Category(null, $_POST["addTags1"]);
				$category1->insert($mysqli);
				$categoryId1 = $category1->getCategoryId();
				$categoryProduct1 = new CategoryProduct($categoryId1, $productId);
				$categoryProduct1->insert($mysqli);
			}
		}

		if(empty($_POST["addTags2"])=== false) {
				$categoryName2 = Category::getCategoryByCategoryName($mysqli, $_POST["addTags2"]);
			if($categoryName2 !== null) {
				$category2 = $categoryName2;
				$categoryId2 = $category2->getCategoryId();
				$categoryProduct2 = new CategoryProduct($categoryId2, $productId);
				$categoryProduct2->insert($mysqli);
			} else {
				$category2 = new Category(null, $_POST["addTags2"]);
				$category2->insert($mysqli);
				$categoryId2 = $category2->getCategoryId();
				$categoryProduct2 = new CategoryProduct($categoryId2, $productId);
				$categoryProduct2->insert($mysqli);
			}
		}

		if(empty($_POST["addTags3"])=== false) {
			$categoryName3 = Category::getCategoryByCategoryName($mysqli, $_POST["addTags3"]);
			if($categoryName3 !== null) {
				$category3 = $categoryName3;
				$categoryId3 = $category3->getCategoryId();
				$categoryProduct3 = new CategoryProduct($categoryId3, $productId);
				$categoryProduct3->insert($mysqli);
			} else {
				$category3 = new Category(null, $_POST["addTags3"]);
				$category3->insert($mysqli);
				$categoryId3 = $category3->getCategoryId();
				$categoryProduct3 = new CategoryProduct($categoryId3, $productId);
				$categoryProduct3->insert($mysqli);
			}
		}
		if(empty($_POST["addTags4"])=== false) {
			$categoryName4 = Category::getCategoryByCategoryName($mysqli, $_POST["addTags4"]);
			if($categoryName4 !== null) {
				$category4 = $categoryName4;
				$categoryId4 = $category4->getCategoryId();
				$categoryProduct4 = new CategoryProduct($categoryId4, $productId);
				$categoryProduct4->insert($mysqli);
			} else {
				$category4 = new Category(null, $_POST["addTags4"]);
				$category4->insert($mysqli);
				$categoryId4 = $category4->getCategoryId();
				$categoryProduct4 = new CategoryProduct($categoryId4, $productId);
				$categoryProduct4->insert($mysqli);
			}
		}
		$imageFileName = $imageBasePath . 'product-' . $productId . '.' . $imageExtension;
		$product->setImagePath($imageFileName);
		$product->update($mysqli);
		move_uploaded_file($_FILES['inputProductImage']['tmp_name'], $imageFileName);
	} else {
		$product = new Product(null, $storeId, "", $_POST["inputProductName"], $_POST["inputProductPrice"], $_POST["inputProductDescription"], $_POST["inputProductPriceType"], $_POST["inputProductWeight"], $_POST["inputStockLimit"]);
		$product->insert($mysqli);
		$productId = $product->getProductId();

		if(empty($_POST["addTags1"])=== false) {

			$categoryName1 = Category::getCategoryByCategoryName($mysqli, $_POST["addTags1"]);
			if($categoryName1 !== null) {
				$category1 = $categoryName1;
				$categoryId1 = $category1->getCategoryId();
				$categoryProduct1 = new CategoryProduct($categoryId1, $productId);
				$categoryProduct1->insert($mysqli);
			} else {
				$category1 = new Category(null, $_POST["addTags1"]);
				$category1->insert($mysqli);
				$categoryId1 = $category1->getCategoryId();
				$categoryProduct1 = new CategoryProduct($categoryId1, $productId);
				$categoryProduct1->insert($mysqli);
			}
		}

		if(empty($_POST["addTags2"])=== false) {
			$categoryName2 = Category::getCategoryByCategoryName($mysqli, $_POST["addTags2"]);
			if($categoryName2 !== null) {
				$category2 = $categoryName2;
				$categoryId2 = $category2->getCategoryId();
				$categoryProduct2 = new CategoryProduct($categoryId2, $productId);
				$categoryProduct2->insert($mysqli);
			} else {
				$category2 = new Category(null, $_POST["addTags2"]);
				$category2->insert($mysqli);
				$categoryId2 = $category2->getCategoryId();
				$categoryProduct2 = new CategoryProduct($categoryId2, $productId);
				$categoryProduct2->insert($mysqli);
			}
		}

		if(empty($_POST["addTags3"])=== false) {
			$categoryName3 = Category::getCategoryByCategoryName($mysqli, $_POST["addTags3"]);
			if($categoryName3 !== null) {
				$category3 = $categoryName3;
				$categoryId3 = $category3->getCategoryId();
				$categoryProduct3 = new CategoryProduct($categoryId3, $productId);
				$categoryProduct3->insert($mysqli);
			} else {
				$category3 = new Category(null, $_POST["addTags3"]);
				$category3->insert($mysqli);
				$categoryId3 = $category3->getCategoryId();
				$categoryProduct3 = new CategoryProduct($categoryId3, $productId);
				$categoryProduct3->insert($mysqli);
			}
		}
		if(empty($_POST["addTags4"])=== false) {
			$categoryName4 = Category::getCategoryByCategoryName($mysqli, $_POST["addTags4"]);
			if($categoryName4 !== null) {
				$category4 = $categoryName4;
				$categoryId4 = $category4->getCategoryId();
				$categoryProduct4 = new CategoryProduct($categoryId4, $productId);
				$categoryProduct4->insert($mysqli);
			} else {
				$category4 = new Category(null, $_POST["addTags4"]);
				$category4->insert($mysqli);
				$categoryId4 = $category4->getCategoryId();
				$categoryProduct4 = new CategoryProduct($categoryId4, $productId);
				$categoryProduct4->insert($mysqli);
			}
		}
	}

	//store the product into the session to be able to edit
	$_SESSION['product'] = array(
		'id' 				=> $product->getProductId(),
		'name'			=> $product->getProductName(),
		'price'	      => $product->getProductPrice(),
		'image'			=> $product->getImagePath(),
		'description'	=> $product->getProductDescription(),
		'weight'       => $product->getProductWeight(),
		'stock'        => $product->getStockLimit(),
		'priceType'    => $product->getProductPriceType()
	);

	echo "<p class=\"alert alert-success\">Product (id = " . $product->getProductId() . ") posted!</p>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}