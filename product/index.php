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
	$store    = Store::getStoreByStoreId($mysqli, 1);

	// get the product id from the current url
	if(!@isset($_GET['product'])) {
		header('Location: ../index.php');
	}

	$productId = filter_var($_GET['product'], FILTER_SANITIZE_NUMBER_INT);

	$product  = Product::getProductByProductId($mysqli, $productId);

	// get all the products of the current product store
	$storeProducts = Product::getAllProductsByStoreId($mysqli, $store->getStoreId());

	// get all the locations from the current store
	$storeLocations = StoreLocation::getAllStoreLocationsByStoreId($mysqli, $store->getStoreId());

	$locations = [];
	if($storeLocations !== null) {
		foreach($storeLocations as $storeLocation) {
			$location = Location::getLocationByLocationId($mysqli, $storeLocation->getLocationId());
			$locations[] = $location;
		}
	}

} catch(Exception $exception) {
	echo 'Exception: '. $exception->getMessage() .'<br/>';
	echo $exception->getFile(). ':' .$exception->getLine();
}

// image path and url setup
$imagePlaceholderSrc = CONTENT_ROOT_URL. 'images/placeholder.jpg';

$productBaseUrl      = CONTENT_ROOT_URL . 'images/product/';
$productBasePath     = CONTENT_ROOT_PATH . 'images/product/';
$productImageSrc     = 'product-'. $product->getProductId() .'.jpg';

$storeBaseUrl  = CONTENT_ROOT_URL . 'images/store/';
$storeBasePath = CONTENT_ROOT_PATH . 'images/store/';
$storeImageSrc  = 'store-'. $store->getStoreId() .'.jpg';

// TODO hash the get url /  mod rewrite
//$salt = bin2hex(openssl_random_pseudo_bytes(16));
//$hash = hash_pbkdf2("sha512", $_POST["password1"], $salt, 2048, 128);

?>

<div class="container-fluid vertical-spacer-60" id="product-page">
	<div class="row">
		<div class="col-sm-5">
			<?php

			if(file_exists($storeBasePath . $storeImageSrc)) {
				echo '<a href="" class="thumbnail"><img src="' . $storeBaseUrl . $storeImageSrc .'" alt="'. $store->getStoreName() .'" class="img-resonsive"/></a>';
			} else {
				echo '<a href="" class="thumbnail"><img src="' . $imagePlaceholderSrc . '" alt="'. $store->getStoreName() .'" class="img-resonsive"/></a>';
			}
			echo '<span class="store-name">By <a href="">'. $store->getStoreName(). '</a></span>';

			?>
		</div>
		<div class="col-sm-7">
			<!-- store products thumbnails -->
			<ul class="thumbnail-links">
			<?php
			if($storeProducts !== null) {
				echo '<li><a href="" class="thumbnail count-store-products">'. count($storeProducts) . ' products</a></li>';

				foreach($storeProducts as $index => $storeProduct) {
					// show at max 4 items
					if(($index + 1) > 4) {
						break;
					}
					$thumbnailSrc            = 'product-'. $storeProduct->getProductId() .'.jpg';

					echo '<li>';

					// link to a product page
					echo '<a href="'. SITE_ROOT_URL .'product/index.php?product='. $storeProduct->getProductId() .'" class="thumbnail">';

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
				<img class="img-responsive" src="<?php echo $productBaseUrl . $productImageSrc; ?>" alt="<?php echo $product->getProductName(); ?>"/>
			<?php } else { ?>
				<img class="img-responsive" src="<?php echo $imagePlaceholderSrc; ?>" alt="<?php echo $product->getProductName(); ?>"/>
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
					} else {
						echo $stockLimit .' available';
					}

					echo '<br/><br/>';

					echo '<p>'.$product->getProductDescription(). '</p>';
					if($stockLimit === null) {
						$stockLimit = 15;
					}

					$maxQuantity = 15;

					// get the # of options to create in the select box
					$quantityLimit = ($stockLimit < $maxQuantity) ? $stockLimit : $maxQuantity;

					if($product->getProductPriceType() === 'u') {

						// select box
						echo 'Select a quantity: <select class="product-quantity" name="productQuantity[]">';

						// creating $quantityLimit # of options
						for($i = 0; $i < $quantityLimit; $i++) {
							if($i === 0) {
								echo '<option selected="selected">' . ($i + 1) . '</option>';
							} else {
								echo '<option>' . ($i + 1) . '</option>';
							}
						}

						echo '</select>';
						// end select box
					} else {

						echo 'Select a weight:';
						echo ' <input class="xs-input" type="text" name="productWeight" id=""/>';
						echo ' lb';
					}

					?>
					<br/>
					<div id="outputArea" class="no-list-style"></div>
					<br/>
					<button class="btn btn-primary" type="submit" id="add-product-to-cart">Add to Cart</button>
				</div><!-- listing-page-cart -->
			</form>

			<div class="fb-share-button" data-href="https://developers.facebook.com/docs/plugins/" data-layout="button_count"></div>
		</div>
	</div>
</div><!-- end container-fluid -->

<?php require_once('../php/lib/footer.php'); ?>