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

<div class="container-fluid mt30">
	<div class="row">
		<div class="col-xs-12">
			<p id="searchResultPage"><?php echo "Search Results For:\" " . $searchq; ?>"</p>
		</div>
	</div>
<!--</div>-->

<!--<div class="container-fluid mt60">-->
	<div class="row">

		<div class="col-sm-3 list-group" id="filter-categories">
			<p>Categories</p>
			<a href="#" class="list-group-item">Vegetables</a>
			<a href="#" class="list-group-item">Fruits</a>
			<a href="#" class="list-group-item">Organic</a>
			<a href="#" class="list-group-item">Shit</a>
		</div>

		<div class="col-sm-9">

<?php

// check if any search was entered
if($searchq == "") {
	echo "<p>No search term entered</p>";
	exit;
}


// try to echo a table per each table searched by
if($stores != null || $locations != null || $products != null) {
	echo '<div class="table-responsive mt30">';
	echo '<table id="searchResults" class="table table-responsive table-striped table-hover">';

}

if($products !== null) {
	echo '<tr>';
	echo '<th></th>';
	echo '<th>Product</th>';
	echo '<th>Description</th>';
	echo '<th>Price</th>';
	echo '<th>Categories</th>';
	echo '</tr>';

	foreach($products as $product) {

		$imagePlaceholderSrc = CONTENT_ROOT_URL. 'images/placeholder.jpg';

		$productName = $product->getProductName();
		$productDescription = $product->getProductDescription();
		$productPrice = $product->getProductPrice();
		$productId = $product->getProductId();

		$getCategories = CategoryProduct::getCategoryProductByProductId($mysqli, $productId);

		foreach($getCategories as $getCategory) {
			$categoryId = $getCategory->getCategoryId();
			$getCategoryNames = Category::getCategoryByCategoryId($mysqli, $categoryId);

			$categoryNames = $getCategoryNames->getCategoryName();

			var_dump($categoryNames);

			foreach($getCategoryNames as $categoryName) {
				$categoryNames = $categoryName->getCategoryName();
				var_dump($categoryNames);
			}
		}

		echo '<tr>';
		if(file_exists($product->getImagePath())) {
			echo '<td><a class="thumbnail" href="'. SITE_ROOT_URL . 'product/index.php?product=' .
					$product->getProductId() .'">
					<img class="img-responsive" src="' . CONTENT_ROOT_URL . 'images/product/' .
					basename($product->getImagePath()) . '">
					</a></td>';
		} else {
			echo '<td><a class="thumbnail" href="'. SITE_ROOT_URL . 'product/index.php?product=' .
					$product->getProductId() .'">
					<img class="img-responsive" src="' . $imagePlaceholderSrc . '">
					</a></td>';
		}
		echo '<td>' . $productName . '</td>';
		echo '<td>' . $productDescription . '</td>';
		echo '<td>$' . $productPrice . '</td>';
		echo '<td>' . $categoryNames . $categoryNames . '</td>';
		echo '</tr>';
	}
}

if($stores !== null) {
	echo '<tr>';
	echo '<th></th>';
	echo '<th>Store</th>';
	echo '<th>Description</th>';
	echo '</tr>';

	foreach($stores as $store) {
		$storeName = $store->getStoreName();
		$storeImage = $store->getImagePath();
		$storeDescription = $store->getStoreDescription();

		$imagePlaceholderSrc = CONTENT_ROOT_URL. 'images/placeholder.jpg';


		echo '<tr>';
		if(file_exists($store->getImagePath())) {
			echo '<td><a class="thumbnail" href="'. SITE_ROOT_URL . 'store/index.php?store=' .
					$store->getStoreId() .'">
					<img class="img-responsive" src="' . CONTENT_ROOT_URL . 'images/store/' .
				basename($store->getImagePath()) . '">
												</a></td>';
		} else {
			echo '<td><a class="thumbnail" href="'. SITE_ROOT_URL . 'store/index.php?store=' .
				$store->getStoreId() .'">
					<img class="img-responsive" src="' . $imagePlaceholderSrc . '">
					</a></td>';
		}
		echo '<td>' . $storeName . '</td>';
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