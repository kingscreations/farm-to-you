<?php

// search result view

// header
$currentDir = dirname(__FILE__);
require_once('../root-path.php');
require_once('../php/lib/header.php');

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// model
require_once("../php/classes/product.php");
require_once("../php/classes/store.php");
require_once("../php/classes/category.php");
require_once("../php/classes/location.php");
require_once("../php/classes/categoryproduct.php");

// get the input from the session
$searchq = $_SESSION['search'];

// connect to database and filter search
try {
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$products = Product::getProductByProductNameAndDescription($mysqli, $searchq);
	$stores = Store::getStoreByStoreName($mysqli, $searchq);
	$locations = Location::getLocationByNameOrAddress($mysqli, $searchq);

} catch(Exception $exception) {
	echo 'Exception: ' . $exception->getMessage() . '<br/>';
	echo $exception->getFile() . ':' . $exception->getLine();
}

?>


<div class="container-fluid mt60">
	<div class="row">
		<div class="col-sm-12">

<?php

// check if any search was entered
if($searchq == "") {
	echo "<p>No search term entered</p>";
	exit;
}

// try to echo a table per each table searched by
if($stores != null || $locations != null || $products != null) {
	echo '<div class="table-responsive">';
	echo '<table id="searchResults" class="table table-condensed table-striped table-bordered table-hover no-margin">';
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
	if($stores != null || $locations != null || $products != null) {
		echo '</table>';
		echo '</div>';
	}




//this counts the number or results - and if there wasn't any it gives them a little message explaining that
	if($stores === null && $locations === null && $products === null) {
		echo "<p class=\"alert alert-danger\">Sorry, but we can not find an entry to match your query</p><br><br>";
//and we remind them what they searched for
		echo "<b>Searched For:</b> " . $searchq;
	}


?>

		</div>
	</div>
</div><!-- end container-fluid -->

<?php require_once('../php/lib/footer.php');