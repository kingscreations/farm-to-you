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
require_once("../php/classes/store.php");
require_once("../php/classes/profile.php");
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

	// get the product id from the current url
	if(!@isset($_GET['store'])) {
		header('Location: ../php/lib/404.php');
	} else {
		$storeId = $_GET['store'];
	}

	$storeId = filter_var($storeId, FILTER_SANITIZE_NUMBER_INT);
	$store = Store::getStoreByStoreId($mysqli, $storeId);

	// get all the products of the current product store
	$storeProducts = Product::getAllProductsByStoreId($mysqli, $store->getStoreId());

	// get all the locations from the current store
	$storeLocations = StoreLocation::getAllStoreLocationsByStoreId($mysqli, $store->getStoreId());

	// get the store owner
	$storeOwner = Profile::getProfileByProfileId($mysqli, $store->getProfileId());

//	$locations = [];
//	if($storeLocations !== null) {
//		foreach($storeLocations as $storeLocation) {
//			$location = Location::getLocationByLocationId($mysqli, $storeLocation->getLocationId());
//			$locations[] = $location;
//		}
//	}

	if($storeProducts === null || $storeLocations === null) {
		header('Location: ../php/lib/404.php');
	}

} catch(Exception $exception) {
	echo 'Exception: '. $exception->getMessage() .'<br/>';
	echo $exception->getFile(). ':' .$exception->getLine();
}

$storeBaseUrl  = CONTENT_ROOT_URL . 'images/store/';
$storeBasePath = CONTENT_ROOT_PATH . 'images/store/';
$storeImageSrc  = basename($store->getImagePath());

?>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-3" id="store-menu">
			<div class="list-group">
				<span class="list-group-item">Store Sections</span>
				<a href="#" class="list-group-item active">Store home</a>
				<a href="#" class="list-group-item">Fruits</a>
				<a href="#" class="list-group-item">Veggies</a>
				<a href="#" class="list-group-item">Nuts</a>
				<a href="#" class="list-group-item">Flowers</a>
			</div>

			<div id="store-owner" class="section">
				<h3>Store Owner</h3>
				<a href=""><?php echo $storeOwner->getFirstName() . ' ' . $storeOwner->getLastName(); ?></a>
			</div>
		</div>
		<div class="col-sm-9" id="store-content">
			<div class="row">
				<div class="col-sm-12">
					<div id="store-id-card">
						<?php $storeLink = SITE_ROOT_URL . 'store/index.php?store='. $store->getStoreId(); ?>

						<div class="store-banner">
							<a href="<?php echo $storeLink; ?>"
								title="<?php echo $store->getStoreDescription(); ?>">
								<img src="<?php echo file_exists($storeBasePath.$storeImageSrc)
									? $storeBaseUrl.$storeImageSrc
									: $imagePlaceHolderSrc; ?>"
									  alt="<?php echo $store->getStoreName(); ?>"
										class="img-responsive"/>
							</a>
						</div><!-- end store-banner -->

						<div class="store-info">
							<div class="row">
								<div class="col-sm-8">
									<h1><?php echo $store->getStoreName(); ?></h1>
								</div>
								<div class="col-sm-4">
									<?php include_once('../php/lib/share-view.php'); ?>
								</div>
							</div>
						</div>

						<div class="announcement">
							<a href="<?php echo $storeLink; ?> id="store-announcement>
								<!-- show only the beginning of the description -->
								<?php

								$storeDescription = $store->getStoreDescription();
								echo strlen($storeDescription) > 20
									? substr($storeDescription, 0, 20) . '...'
									: $storeDescription;

								?>
							</a>
						</div>
					</div><!-- end store-id-card -->
				</div>
			</div>

			<div class="row" id="featured">

			</div>

			<div class="row" id="products">
				<div class="col-sm-12">
					<ul class="products product-listing">
						<?php foreach($storeProducts as $index => $storeProduct) { ?>

							<?php

							$productImageBaseUrl  = CONTENT_ROOT_URL . 'images/product/';
							$productImageBasePath = CONTENT_ROOT_PATH . 'images/product/';
							$productImageSrc  = basename($storeProduct->getImagePath());

							$productUrl = SITE_ROOT_URL.'product/index.php?product='.$storeProduct->getProductId();

							$productDescription = $storeProduct->getProductDescription();

							?>

							<li id="<?php echo $storeProduct->getProductId(); ?>">
								<div class="product-listing-card">
									<a class="product-listing-thumbnail"
										href="<?php echo $productUrl; ?>"
										title="<?php echo $productDescription; ?>"
										>
										<img src="<?php echo (file_exists($productImageBasePath.$productImageSrc)
											? $productImageBaseUrl.$productImageSrc
											: $imagePlaceholderSrc) ?>"
											  alt="<?php echo $productDescription; ?>"
												class="img-responsive"/>
									</a>
									<div class="product-listing-detail">
										<a href="<?php echo $productUrl ?>" class="product-listing-card-title"
											title="<?php echo $productDescription; ?>">
											<?php

											echo strlen($productDescription) > 26
												? substr($productDescription, 0, 26) . '...'
												: $productDescription;

											?>
										</a>
									</div>
								</div><!-- end product-listing card -->
							</li>

						<?php } ?><!-- end of the for each store product loop -->
					</ul>
				</div>
			</div>
		</div><!-- end store-content -->
	</div>
</div>

<?php require_once '../php/lib/footer.php' ?>