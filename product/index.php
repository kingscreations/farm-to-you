<?php

/**
* @author Florian Goussin <florian.goussin@gmail.com>
*/

// header
$currentDir = dirname(__FILE__);
require_once('../root-path.php');
require_once('../php/lib/header.php');

// credentials
require_once('/etc/apache2/capstone-mysql/encrypted-config.php');

// paths file
require_once('../paths.php');

// model
require_once("../php/classes/product.php");
require_once("../php/classes/user.php");
require_once("../php/classes/profile.php");
require_once("../php/classes/store.php");
require_once("../php/classes/location.php");
require_once("../php/classes/storelocation.php");
require_once("../php/classes/orderproduct.php");

// path for the config file
$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";

mysqli_report(MYSQLI_REPORT_STRICT);

try {

	// get the credentials information from the server and connect to the database
	$configArray = readConfig($configFile);

	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	$user     = User::getUserByUserId($mysqli, 1);
	$profile  = Profile::getProfileByProfileId($mysqli, 1);

	// get the product id from the current url
	if(!@isset($_GET['store']) && !@isset($_GET['product'])) {
		header('Location: ../php/lib/404.php');
	}

	$productId = filter_var($_GET['product'], FILTER_SANITIZE_NUMBER_INT);
	$product  = Product::getProductByProductId($mysqli, $productId);

	if(@isset($_GET['store'])) {
		$storeId = filter_var($_GET['store'], FILTER_SANITIZE_NUMBER_INT);
	} else {
		$storeId = $product->getStoreId();
	}

	$store    = Store::getStoreByStoreId($mysqli, $storeId);

	// get all the products of the current product store
	$storeProducts = Product::getAllProductsByStoreId($mysqli, $store->getStoreId());

	if(!in_array($product, $storeProducts)) {
		header('Location: ../php/lib/404.php');
	}

	// get all the locations from the current store
	$storeLocations = StoreLocation::getAllStoreLocationsByStoreId($mysqli, $store->getStoreId());

	$locations = [];
	if($storeLocations !== null) {
		foreach($storeLocations as $storeLocation) {
			$location = Location::getLocationByLocationId($mysqli, $storeLocation->getLocationId());
			$locations[] = $location;
		}
	}

	$mysqli->close();

} catch(Exception $exception) {
	echo 'Exception: '. $exception->getMessage() .'<br/>';
	echo $exception->getFile(). ':' .$exception->getLine();
}

// image path and url setup
$imagePlaceholderSrc = CONTENT_ROOT_URL. 'images/placeholder.jpg';

$productBaseUrl      = CONTENT_ROOT_URL . 'images/product/';
$productBasePath     = CONTENT_ROOT_PATH . 'images/product/';
$productImageSrc     = basename($product->getImagePath());

$storeBaseUrl  = CONTENT_ROOT_URL . 'images/store/';
$storeBasePath = CONTENT_ROOT_PATH . 'images/store/';
$storeImageSrc  = basename($store->getImagePath());

?>

<div class="container-fluid mt60" id="product">
	<div class="row">
		<div class="col-sm-5 col-xs-7">
			<?php

			$storeLink = SITE_ROOT_URL . 'store/index.php?store='. $store->getStoreId();

			if(file_exists($storeBasePath . $storeImageSrc)) {
				echo '<a href="' . $storeLink . '" class="thumbnail"><img src="' . $storeBaseUrl . $storeImageSrc .'" alt="'.
					$store->getStoreName() .'" class="img-responsive"/></a>';
			} else {
				echo '<a href="' . $storeLink . '" class="thumbnail"><img src="' . $imagePlaceholderSrc . '" alt="'. $store->getStoreName() .
					'" class="img-responsive"/></a>';
			}
			echo '<span class="store-name">By <a href="' . $storeLink . '">'. $store->getStoreName(). '</a></span>';

			?>
		</div>
		<div class="col-sm-7 col-xs-5">
			<!-- store products thumbnails -->
			<ul class="thumbnail-links">
			<?php
			if($storeProducts !== null) {
				echo '<li class="hidden-xs"><a href="' . $storeLink . '" class="count-store-products"><span class="">'. count($storeProducts) .
					' <small>products</small></a></li>';

				// randomize the products
				shuffle($storeProducts);

				// show the products thumbnails
				foreach($storeProducts as $index => $storeProduct) {
					// show at max 4 items
					if($index > 3) {
						break;
					}

					// rely only on the image path to get the file name
					$thumbnailSrc = basename($storeProduct->getImagePath());

					if($index > 1) {
						echo '<li class="hidden-xs">';
					} else {
						echo '<li>';
					}

					// link to a product page
					echo '<a href="'. SITE_ROOT_URL .'product/index.php?product='. $storeProduct->getProductId() .
						'" class="thumbnail">';

					if(file_exists($productBasePath . $thumbnailSrc)) {
						echo '<img class="img-responsive" src="' . $productBaseUrl . $thumbnailSrc . '">';
					} else {
						echo '<img class="img-responsive" src="' . $imagePlaceholderSrc . '">';
					}
					echo '</a>';
					echo '</li>';
				}

			}
			?>
			</ul>
		</div><!-- end col-sm-7 -->
	</div>
</div><!-- end container-fluid -->

<div class="container-fluid white-container">
	<div class="row">
		<div class="col-sm-7">
			<?php if(file_exists($productBasePath . $productImageSrc)) { ?>
				<img class="img-responsive" src="<?php echo $productBaseUrl . $productImageSrc; ?>"
					  alt="<?php echo $product->getProductName(); ?>"/>
			<?php } else { ?>
				<img class="img-responsive" src="<?php echo $imagePlaceholderSrc; ?>"
					  alt="<?php echo $product->getProductName(); ?>"/>
			<?php } ?>
		</div>
		<div class="col-sm-5">
			<form id="product-controller" action="../php/forms/product-controller.php" method="post">
				<div id="listing-page-cart">
					<h1><?php echo $product->getProductName(); ?></h1>

					<span class="currency-value">$<?php echo $product->getProductPrice(); ?> USD</span><br/>
					<?php

					$stockLimit = $product->getStockLimit();
					if($stockLimit < 15) {
						echo 'Only '. $stockLimit .' available';
					} else if($stockLimit > 60) {
						echo 'A lot available';
					} else {
						echo $stockLimit .' available';
					}

					echo '<br/><br/>';

					echo '<p>'.$product->getProductDescription(). '</p>';
					if($stockLimit === null) {
						$stockLimit = 99;
					}

					// select box
					echo 'Select a quantity: <select class="product-quantity" name="productQuantity">';

					// creating $quantityLimit # of options
					for($i = 0; $i < $stockLimit; $i++) {
						if($i === 0) {
							echo '<option selected="selected">' . ($i + 1) . '</option>';
						} else {
							echo '<option>' . ($i + 1) . '</option>';
						}
					}

					echo '</select>';
					// end select box

					?>
					<button class="btn btn-primary" type="submit" id="add-product-to-cart">Add to Cart</button>
					<input name="product" type="hidden" value="<?php echo $product->getProductId(); ?>"/>
				</div><!-- listing-page-cart -->
				<div id="outputArea" class="no-list-style"></div>
			</form>

			<?php include_once('../php/lib/share-view.php'); ?>
		</div>
	</div>
</div><!-- end container-fluid -->

<?php require_once('../php/lib/footer.php'); ?>