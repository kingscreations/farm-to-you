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

	// TODO link this page with the index page AND search page
	$product  = Product::getProductByProductId($mysqli, 1);

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

	// image path and url setup
	$baseUrl             = CONTENT_ROOT_URL . 'images/product/';
	$basePath            = CONTENT_ROOT_PATH . 'images/product/';
	$imagePlaceholderSrc = CONTENT_ROOT_URL. 'images/placeholder.jpg';
	$imageSrc            = 'product-'. $product->getProductId() .'.jpg';

} catch(Exception $exception) {
	echo 'Exception: '. $exception->getMessage() .'<br/>';
	echo $exception->getFile(). ':' .$exception->getLine();
}

?>

<div class="row">
	<div class="col-sm-5">
		<?php

		echo '<a href="">'. $store->getStoreName(). '</a>';

		?>
	</div>
	<div class="col-sm-7">
		<ul class="thumbnail-links">
		<?php
		if($storeProducts !== null) {
			foreach($storeProducts as $product) {
				echo '<li>';
				echo $product->getProductName();
				echo '<a href="" class="thumbnail">';

				if(file_exists($basePath . $imageSrc)) {
					echo '<img class="img-responsive" src="' . $baseUrl . $imageSrc . '">';
				} else {
					echo '<img class="img-responsive" src="' . $imagePlaceholderSrc . '">';
				}
				echo '</a>';
				echo '</li>';
			}
		}
		?>
		</ul>
	</div>
</div>

<div class="row">
	<div class="col-sm-7">
		<?php if(file_exists($basePath . $imageSrc)) { ?>
			<img class="img-responsive" src="<?php echo $baseUrl . $imageSrc; ?>" alt="<?php echo $product->getProductName(); ?>"/>
		<?php } else { ?>
			<img class="img-responsive" src="<?php echo $imagePlaceholderSrc; ?>" alt="<?php echo $product->getProductName(); ?>"/>
		<?php } ?>
	</div>
	<div class="col-sm-5">
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

			echo '<br/>';

			if($stockLimit === null) {
				$stockLimit = 15;
			}

			$maxQuantity = 15;

			// get the # of options to create in the select box
			$quantityLimit = ($stockLimit < $maxQuantity) ? $stockLimit : $maxQuantity;

			// select box
			echo '<td><select class="product-quantity" id="product'. $counter .'-quantity" name="productQuantity[]">';

			// creating $quantityLimit # of options
			for($i = 0; $i < $quantityLimit; $i++) {
				if(($i + 1) === $sessionProduct['quantity']) {
					echo '<option selected="selected">' . ($i + 1) . '</option>';
				} else {
					echo '<option>' . ($i + 1) . '</option>';
				}
			}

			echo '</select></td>';
			// end select box

			?>
		</div>
	</div>
</div>