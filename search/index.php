<?php

// search result view

// header
$currentDir = dirname(__FILE__);
require_once('../root-path.php');

session_start();

if(!@isset($_GET['searchq'])) {
	header('Location: ../php/lib/404.php');
}

session_abort();

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

<div class="container-fluid transparent-form" >
<!--	<div class="row">-->
		<div class>
			<p id="searchResultPage"><?php echo 'Search Results For: <span id="search-term">' . $searchq . '</span>'; ?></p>

<!--</div>-->

<!--<div class="container-fluid mt60">-->
	<div class="row">

		<div class="col-md-6 list-group transparent-menu hidden-xs mt25" id="filter-categories">
			<p class="list-group-item list-group-item-info">Categories</p>
			<?php
			if($products !== null) { ?>
			<p class="disabled list-group-item list-group-item-info static">Products</p>
			<?php } ?>
			<a href="<?php echo SITE_ROOT_URL . 'search/index.php?searchq=' . $searchq; ?>"
				id="category-list category-item"
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

		<div class="dropdown visible-xs" style="position:relative">
			<a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Categories<span class="caret"></span></a>
			<ul class="dropdown-menu search-dropdown">

				<?php if($products !== null) { ?>
				<li><p class="disabled static">Products</p></li>
				<?php } ?>

				<li><a href="<?php echo SITE_ROOT_URL . 'search/index.php?searchq=' . $searchq; ?>"
				id="category-list" class="list-group-item <?php echo ($categoryNameFromUrl === '') ? 'active' : '';
				?> static">All</a></li>

				<?php
				if($products !== null) {
				foreach($categories as $category) { ?>
				<li><a href="<?php echo SITE_ROOT_URL . 'search/index.php?searchq=' . $searchq . '&category=' . $category->getCategoryName(); ?>"
					id="category-list"
					class="list-group-item <?php echo ($categoryNameFromUrl === $category->getCategoryName()) ? 'active' : ''; ?>"><?php echo $category->getCategoryName(); ?></a></li>
				<?php }
				}?>
			</ul>
		</div>
		<br>


<?php

// check if any search was entered
if($searchq == "") {
	echo "<p>No search term entered</p>";
	exit;
}


// try to echo a table per each table searched by
if($stores != null || $locations != null || $products != null) {
	echo '<div class="table-responsive mt25">';
	echo '<table id="searchResults" class="table table-responsive table-striped table-hover">';

}

if($products !== null) {
	echo '<tr>';
	echo '<th></th>';
	echo '<th class="table-header">Product</th>';
	echo '<th class="hidden-xs">Description</th>';
	echo '<th class="table-header">Price</th>';
	echo '</tr>';

	foreach($products as $product) {

		$imagePlaceholderSrc = SITE_ROOT_URL. 'images/placeholder.png';

		$productName = $product->getProductName();
		$productDescription = $product->getProductDescription();
		$productPrice = number_format((float)$product->getProductPrice(), 2, '.', '');
		$productId = $product->getProductId();


		echo '<tr id="product-' . $productId . '">';
		if(file_exists($product->getImagePath())) {
			echo '<td><a class="thumbnail search-image" href="'. SITE_ROOT_URL . 'product/index.php?product=' .
					$product->getProductId() .'">
					<img class="img-responsive" src="' . CONTENT_ROOT_URL . 'images/product/' .
					basename($product->getImagePath()) . '">
					</a></td>';
		} else {
			echo '<td><a class="thumbnail search-image" href="'. SITE_ROOT_URL . 'product/index.php?product=' .
					$product->getProductId() .'">
					<img class="img-responsive" src="' . $imagePlaceholderSrc . '">
					</a></td>';
		}
		echo '<td><a href="'. SITE_ROOT_URL . 'product/index.php?product=' .
			$product->getProductId(). '">'. $productName . '</a></td>';
		echo '<td class="hidden-xs">' . $productDescription . '</td>';
		echo '<td>$' . $productPrice . '</td>';
		echo '</tr>';
	}
}

if($stores !== null) {
	echo '<tr id="store-">';
	echo '<th class="table-header"></th>';
	echo '<th class="table-header">Store</th>';
	echo '<th class="hidden-xs">Description</th>';
	echo '<th class="visible-xs"></th>';
	echo '</tr>';

	foreach($stores as $store) {
		$storeName = $store->getStoreName();
		$storeImage = $store->getImagePath();
		$storeDescription = $store->getStoreDescription();
		$storeId= $store->getStoreId();

		$imagePlaceholderSrc = SITE_ROOT_URL. 'images/placeholder.png';


		echo '<tr id="store-' . $storeId . '">';
		if(file_exists($store->getImagePath())) {
			echo '<td class="wide-column"><a class="thumbnail search-image" href="'. SITE_ROOT_URL . 'store/index.php?store=' .
					$store->getStoreId() .'">
					<img class="img-responsive" src="' . CONTENT_ROOT_URL . 'images/store/' .
				basename($store->getImagePath()) . '">
												</a></td>';
		} else {
			echo '<td class="wide-column"><a class="thumbnail search-image" href="'. SITE_ROOT_URL . 'store/index.php?store=' .
				$store->getStoreId() .'">
					<img class="img-responsive" src="' . $imagePlaceholderSrc . '">
					</a></td>';
		}
		echo '<td class="wide-column">' . $storeName . '</td>';
		echo '<td class="hidden-xs">' . $storeDescription . '</td>';
		echo '<th class="visible-xs"></th>';
	}
}

	if($locations !== null) {
		echo '<tr id="location-">';
		echo '<th class="table-header">Location</th>';
		echo '<th class="table-header">Address</th>';
		echo '<th class="table-header">City</th>';
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
		echo "<div class='alert-message'>
					<p class=\"alert alert-danger\">Sorry, no results.</p><br>
				</div>";
//and we remind them what they searched for
		echo "<b>Searched For: </b>" . $searchq;

	}


?>

		</div>
	</div>
	</div>
	</div>
</div><!-- end container-fluid -->

<script src="../js/search.js"></script>

<?php require_once('../php/lib/footer.php');