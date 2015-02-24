<?php
$currentDir = dirname(__FILE__);

require_once '../../root-path.php';
require_once("../lib/header.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/product.php");
require_once("../classes/store.php");
require_once("../classes/category.php");
require_once("../classes/location.php");
require_once("../classes/categoryproduct.php");
require_once("../lib/footer.php");



$searchq = $_POST["inputSearch"];
$searching = $_POST["searching"];

// this is only displayed if they have submitted the form
if ($searching =="yes") {
	echo "<h2>Results</h2><p>";

	// if they did not enter a search term we give them an error
	if($searchq == "") {
		echo "<p>No search term entered</p>";
		exit;
	}
}

// connect to database and filter search
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);


$products = Product::getProductByProductNameAndDescription($mysqli, $searchq);
$stores = Store::getStoreByStoreName($mysqli, $searchq);
$locations = Location::getLocationByNameOrAddress($mysqli, $searchq);


// try to echo a table per each table searched by
if($stores != null || $locations != null || $products != null) {
	echo '<table id="searchResults" class="table table-responsive">';
}


	if($products !== null) {
			echo '<tr>';
			echo '<th>Product</th>';
			echo '<th>Description</th>';
			echo '<th>Price</th>';
			echo '</tr>';
		foreach($products as $product) {
			$productName = $product->getProductName();
			$productDescription = $product->getProductDescription();
			$productPrice = $product->getProductPrice();
			echo '<tr>';
			echo '<td>' . $productName . '</td>';
			echo '<td>' . $productDescription . '</td>';
			echo '<td>' . $productPrice . '</td>';
			echo '</tr>';
		}
	}
if($stores !== null) {
		echo '<tr>';
		echo '<th>Store</th>';
		echo '<th>Image</th>';
		echo '<th>Description</th>';
		echo '</tr>';
		foreach($stores as $store) {
			$storeName = $store->getStoreName();
			$storeImage = $store->getImagePath();
			$storeDescription = $store->getStoreDescription();
			echo '<tr>';
			echo '<td>' . $storeName . '</td>';
			echo '<td>' . $storeImage . '</td>';
			echo '<td>' . $storeDescription . '</td>';
		}
	}

	if($locations !== null) {
			echo '<tr>';
			echo '<th>Location</th>';
			echo '<th>Address</th>';
			echo '<th>City</th>';
			echo '</tr>';
		foreach($locations as $location) {
			$locationName = $location->getLocationName();
			$locationAddress1 = $location->getAddress1();
			$locationCity = $location->getCity();
			echo '<tr>';
			echo '<td>' . $locationName . '</td>';
			echo '<td>' . $locationAddress1 . '</td>';
			echo '<td>' . $locationCity . '</td>';
			echo '</tr>';
		}
	}
	if($stores != null || $locations != null) {
		echo '</table>';
	}




//this counts the number or results - and if there wasn't any it gives them a little message explaining that
	if($stores === null && $locations === null && $products === null) {
		echo "<p class=\"alert alert-danger\">Sorry, but we can not find an entry to match your query</p><br><br>";
//and we remind them what they searched for
		echo "<b>Searched For:</b> " . $searchq;
	}


?>