<?php

/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */


if(!@isset($_SESSION['products'])) {
	header('Location: ../php/lib/404.php');
}

// header
$currentDir = dirname(__FILE__);
require_once '../root-path.php';
require_once '../php/lib/header.php';


// model
require_once("../php/classes/location.php");
require_once("../php/classes/user.php");
require_once("../php/classes/profile.php");
require_once("../php/classes/store.php");
require_once("../php/classes/storelocation.php");
require_once("../php/classes/product.php");

// credentials
require_once '/etc/apache2/capstone-mysql/encrypted-config.php';
$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";

// errors report
mysqli_report(MYSQLI_REPORT_STRICT);

// TODO get rid of the following hardcoded variables:
if(@isset($_SESSION['user']['id'])) {
	$userId = $_SESSION['user']['id'];
} else {
	$userId = 1;
}

if(@isset($_SESSION['profile']['id'])) {
	$profileId = $_SESSION['profile']['id'];
} else {
	$profileId = 1;
}

try {
	// get the credentials information from the server and connect to the database
	$configArray = readConfig($configFile);

	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	$user = User::getUserByUserId($mysqli, $userId);
	$profile = User::getUserByUserId($mysqli, $profileId);

	// get all the products from the cart and get the stores (one store per product)
	$products = [];
	$stores = [];

	foreach($_SESSION['products'] as $sessionProductId => $sessionProduct) {
		$product = Product::getProductByProductId($mysqli, $sessionProductId);
		$store   = Store::getStoreByStoreId($mysqli, $product->getStoreId());

		$products[] = $product;
		$stores[]   = $store;
	}

	// get rid of all the duplicates entries (2 products could be from the same store)
	$stores = array_unique($stores);

	// get all the store locations from the stores
	$storeLocationsFromAllStores = [];
	$mergeStoreLocationsFromAllStores = [];

	foreach($stores as $store) {
		$storeLocations = StoreLocation::getAllStoreLocationsByStoreId($mysqli, $store->getStoreId());

		// construct a giant two dimension array with all the storeLocations
		$storeLocationsFromAllStores[] = $storeLocations;

		// construct a giant one dimension array with all the storeLocations
		$mergeStoreLocationsFromAllStores = array_merge($storeLocations, $mergeStoreLocationsFromAllStores);
	}

	$commonLocations = [];
	foreach($mergeStoreLocationsFromAllStores as $storeLocation) {

		$matchCounter = 0;
		$locationCompared = null;

		// from the current location of this current store, see if the other stores have the same one
		foreach($mergeStoreLocationsFromAllStores as $storeLocationToCompare) {
			$location          = Location::getLocationByLocationId($mysqli, $storeLocation->getLocationId());
			$locationToCompare = Location::getLocationByLocationId($mysqli, $storeLocationToCompare->getLocationId());

			// same location from two different stores
			if($location->equals($locationToCompare) && $storeLocation->getStoreId() !== $storeLocationToCompare->getStoreId()) {
				$matchCounter++;
				$locationCompared = $location;
			}
		}

		// if the number of matches is the same than the number of stores but the current used to compare
		if($matchCounter === (count($stores) - 1)) {
			$commonLocations[] = $locationCompared;
		}
	}


//	$storeLocations = array_unique($storeLocations);

	$mysqli->close();

} catch(Exception $exception) {
	echo "Exception: " . $exception->getMessage() . "<br/>";
	echo $exception->getFile() . ":" . $exception->getLine();
}

?>

<div class="container fluid">
	<div class="row">
		<div class="col-sm-12">
			<form id="checkoutShippingController" action="../php/forms/checkout-shipping-controller.php" method="post" novalidate>
				<h2>You don't have any choice for the pickup location even if you are supposed to</h2>
				<p>The chosen location you "have chosen" is:</p>
				<?php var_dump($commonLocations); ?>
				<ul>
					<li>Grower's Market</li>
					<li>Robinson Park</li>
					<li>87102, Albuquerque NM</li>
				</ul>

				<p id="outputArea"></p>
				<input type="submit" value="Continue to checkout" class="btn btn-default push-right" id="checkout-shipping-submit">
			</form>
		</div>
	</div><!-- end row -->

	<div class="row">
		<div class="col-sm-4">
			<div class="list-group">
				<span class="list-group-item">Pickup locations</span>
				<a href="#" class="list-group-item active">Store home</a>
			</div>
		</div>
		<div class="col-sm-8">

		</div>
	</div>
</div><!-- end container-fluid -->

<?php require_once('../php/lib/footer.php'); ?>