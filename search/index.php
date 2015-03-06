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
?>


<?php

// get the variables from the URL
if(@isset($_GET['searchq'])) {
	$searchq = filter_var($_GET['searchq'], FILTER_SANITIZE_STRING);
} else {
	throw new InvalidArgumentException('searchq missing in the URL');
	exit();
}

if(@isset($_GET['category'])) {
	$categoryNameFromUrl = filter_var($_GET['category'], FILTER_SANITIZE_STRING);
} else {
	$categoryNameFromUrl = '';
}

// connect to database and filter search
try {
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$products = Product::getProductByProductNameAndDescription($mysqli, $searchq);
	$stores = Store::getStoreByStoreName($mysqli, $searchq);
	$locations = Location::getLocationByNameOrAddress($mysqli, $searchq);

	if($products !== null) {
		// test each of the products of the store to see if it matches the category
		$categoryProducts = [];
		foreach($products as $product) {

			$productId = $product->getProductId();
			$resultCategoryProducts = CategoryProduct::getCategoryProductByProductId($mysqli, $productId);

			if($resultCategoryProducts !== null) {
				$categoryProducts = array_merge($categoryProducts, $resultCategoryProducts);
			}
		}

		// get all the categories
		$categories = [];
		foreach($categoryProducts as $categoryProduct) {
			$categories[] = Category::getCategoryByCategoryId($mysqli, $categoryProduct->getCategoryId());
		}

		// delete duplicates
		$categories = array_unique($categories, SORT_REGULAR);
	}

// FEATURE THAT ALLOW UNIQUE URL by search term AND category name
	// filter the products by the category name
//	if($products !== null && $categoryNameFromUrl !== '') {
//		// test each of the products of the store to see if it matches the category
//		$productsToShow = [];
//		foreach($products as $product) {
//
//			$categoryProducts = CategoryProduct::getCategoryProductByProductId($mysqli, $product->getProductId());
//
//			// just go directly to the next iteration since no category is available for this product
//			if($categoryProducts === null) {
//				continue;
//			}
//
//			foreach($categoryProducts as $categoryProduct) {
//				$category = Category::getCategoryByCategoryId($mysqli, $categoryProduct->getCategoryId());
//
//				// if there is a match, then grab the product id inside $productIds
//				if($category->getCategoryName() === $categoryNameFromUrl) {
//					$productsToShow[] = $product;
//					break;
//				}
//			}
//		}
//
//		$products = $productsToShow;
//	}


} catch(Exception $exception) {
	echo 'Exception: ' . $exception->getMessage() . '<br/>';
	echo $exception->getFile() . ':' . $exception->getLine();
}

?>

<script src="../js/search.js"></script>

<div class="container-fluid mt30 white-container" >
	<div class="row">
		<div class="col-xs-12">
			<p id="searchResultPage"><?php echo 'Search Results For: <span id="search-term">' . $searchq . '</span>'; ?></p>
		</div>
	</div>
<!--</div>-->

<!--<div class="container-fluid mt60">-->
	<div class="row">

		<div class="col-sm-3 list-group" id="filter-categories">
			<p class="list-group-item list-group-item-info">Categories</p>
			</br>
			<?php
			if($products !== null) { ?>
			<p class="disabled list-group-item list-group-item-info static">Products</p>
			<?php } ?>
			<a href="<?php echo SITE_ROOT_URL . 'search/index.php?searchq=' . $searchq; ?>"
				id="category-list"
				class="list-group-item <?php echo ($categoryNameFromUrl === '') ? 'active' : ''; ?> static">All</a>
			<?php
			if($products !== null) {
				foreach($categories as $category) { ?>
					<a href="<?php echo SITE_ROOT_URL . 'search/index.php?searchq=' . $searchq . '&category=' . $category->getCategoryName(); ?>"
						id="category-list"
						class="list-group-item <?php echo ($categoryNameFromUrl === $category->getCategoryName()) ? 'active' : ''; ?>"><?php echo $category->getCategoryName(); ?></a>
				<?php }
			}?>
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
	echo '</tr>';

	foreach($products as $product) {

		$imagePlaceholderSrc = SITE_ROOT_URL. 'images/placeholder.png';

		$productName = $product->getProductName();
		$productDescription = $product->getProductDescription();
		$productPrice = $product->getProductPrice();
		$productId = $product->getProductId();


		echo '<tr id="product-' . $productId . '">';
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
		echo '<td><a href="'. SITE_ROOT_URL . 'product/index.php?product=' .
			$product->getProductId(). '">'. $productName . '</a></td>';
		echo '<td>' . $productDescription . '</td>';
		echo '<td>$' . $productPrice . '</td>';
		echo '</tr>';
	}
}

if($stores !== null) {
	echo '<tr id="store-">';
	echo '<th></th>';
	echo '<th>Store</th>';
	echo '<th>Description</th>';
	echo '</tr>';

	foreach($stores as $store) {
		$storeName = $store->getStoreName();
		$storeImage = $store->getImagePath();
		$storeDescription = $store->getStoreDescription();
		$storeId= $store->getStoreId();

		$imagePlaceholderSrc = SITE_ROOT_URL. 'images/placeholder.png';


		echo '<tr id="store-' . $storeId . '">';
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
		echo '<tr id="location-">';
		echo '<th>Location</th>';
		echo '<th>Address</th>';
		echo '<th>City</th>';
		echo '</tr>';

		foreach($locations as $location) {
			$locationName = $location->getLocationName();
			$locationAddress1 = $location->getAddress1();
			$locationCity = $location->getCity();
			$locationId = $location->getLocationId();

			echo '<tr id="location-' . $locationId . '">';
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
		echo "<b>Searched For:</b>" . $searchq;

	}


?>

		</div>
	</div>
</div><!-- end container-fluid -->

<script src="../js/search.js"></script>

<?php require_once('../php/lib/footer.php');