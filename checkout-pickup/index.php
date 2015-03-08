<?php

/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */


//if(!@isset($_SESSION['products'])) {
//	header('Location: ../php/lib/404.php');
//}

// header
$currentDir = dirname(__FILE__);
require_once '../root-path.php';

session_start();

if(!@isset($_SESSION['profileId'])) {
	header('Location: ../sign-in/index.php');
}

session_abort();

require_once '../php/lib/header.php';

?>

<div class="container-fluid">

<?php

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

// TODO get rid of the following hardcoded variables: DONE!

$userId = $_SESSION['user']['id'];

$profileId = $_SESSION['profileId'];

try {
	// get the credentials information from the server and connect to the database
	$configArray = readConfig($configFile);

	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

//	$user = User::getUserByUserId($mysqli, $userId);
//	$profile = User::getUserByUserId($mysqli, $profileId);

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
	$stores = array_unique($stores, SORT_REGULAR);

	// get all the store locations from the stores
	$storeLocationsByStore = [];
	$locationsByStore = [];

	$mergeStoreLocationsFromAllStores = [];

	foreach($stores as $store) {
		$storeLocations = StoreLocation::getAllStoreLocationsByStoreId($mysqli, $store->getStoreId());

		// construct a giant two dimension array with all the storeLocations
		$storeLocationsByStore[] = $storeLocations;

		$locations = [];
		foreach($storeLocations as $storeLocation) {
			$locations[] = Location::getLocationByLocationId($mysqli, $storeLocation->getLocationId());
		}

		// save the locations by store
		$locationsByStore[] = $locations;

		// construct a giant one dimension array with all the storeLocations
		$mergeStoreLocationsFromAllStores = array_merge($storeLocations, $mergeStoreLocationsFromAllStores);
	}

	$commonLocations = [];
	for($i = 0; $i < count($mergeStoreLocationsFromAllStores); $i++) {
		$storeLocation = $mergeStoreLocationsFromAllStores[$i];

		$matchCounter = 0;
		$commonLocation = null;

		// from the current location of this current store, see if the other stores have the same one
		// $j = $i + 1 to not waste time comparing duplicates entries :)
		for($j = $i + 1; $j < count($mergeStoreLocationsFromAllStores); $j++) {
			$storeLocationToCompare = $mergeStoreLocationsFromAllStores[$j];

			$location          = Location::getLocationByLocationId($mysqli, $storeLocation->getLocationId());
			$locationToCompare = Location::getLocationByLocationId($mysqli, $storeLocationToCompare->getLocationId());

			// same location from two different stores
			if($location->equals($locationToCompare)) { // && $storeLocation->getStoreId() !== $storeLocationToCompare->getStoreId()) {
				$matchCounter++;
				$commonLocation = $location;
			}
		}

		// if the number of matches is the same than the number of stores but the current used to compare
		if($matchCounter !== 0 && $matchCounter === (count($stores) - 1)) {
			$commonLocations[] = $commonLocation;
		}
	}

} catch(Exception $exception) {
	echo "Exception: " . $exception->getMessage() . "<br/>";
	echo $exception->getFile() . ":" . $exception->getLine();
}

?>


	<form id="checkoutShippingController" action="../php/forms/checkout-pickup-controller.php" method="post" novalidate onsubmit="event.preventDefault()">
		<?php echo generateInputTags(); ?>
		<div class="row">
			<div class="col-sm-12">
				<h1>Pickup locations</h1>
				<?php if(count($commonLocations) !== 0) { ?>
					<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

						<!--	same pick up location -->
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingOne">
								<h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
										Common pick up location
									</a>
								</h4>
							</div>
							<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-4">
											<div class="list-group">
												<span class="list-group-item">
													<?php echo count($commonLocations) > 1 ? 'Pickup locations' : 'Pickup location'; ?>
												</span>
												<?php foreach($commonLocations as $commonLocation) { ?>
												<a href="#" class="list-group-item active">
													<?php echo $commonLocation->getLocationName() ?><br/>
													<?php echo $commonLocation->getAddress1(); ?><br/>
													<?php echo ($commonLocation->getAddress2() !== '')
														? $commonLocation->getAddress2() . '<br/>'
														: ''; ?>
													<?php echo $commonLocation->getCity() . ' ' . $commonLocation->getState() . ' ' .
														$commonLocation->getZipCode(); ?><br/>
												</a>
												<?php } ?>
											</div>
										</div>
										<div class="col-sm-8">

										</div>
									</div><!-- end row -->
								</div>
							</div>
						</div>

						<!-- pick up location per store -->
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingTwo">
								<h4 class="panel-title">
									<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
										Pick-up location per store
									</a>
								</h4>
							</div>
							<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
								<div class="panel-body">
									<?php for($i = 0; $i < count($stores); $i++) {

										// get the array of store locations for this store
										$locations = $locationsByStore[$i];
										?>
										<div class="row">
											<div class="col-sm-4">
												<div class="list-group pickup-locations">
																	<span class="list-group-item">
																		<?php echo $store->getStoreName(); ?>
																	</span>
													<?php foreach($locations as $location) { ?>
														<a href="#" class="list-group-item">
															<?php echo $location->getLocationName() ?><br/>
															<?php echo $location->getAddress1(); ?><br/>
															<?php echo ($location->getAddress2() !== '')
																? $location->getAddress2() . '<br/>'
																: ''; ?>
															<?php echo $location->getCity() . ' ' . $location->getState() . ' ' .
																$location->getZipCode(); ?><br/>
														</a>
													<?php } ?>
												</div>
											</div>
											<div class="col-sm-8">

												<!-- google map canvas -->
												<div id="map-canvas"></div>
											</div>
										</div><!-- end row -->
									<?php } ?><!-- end for each store -->

								</div>
							</div>
						</div>
					</div><!-- end accordion -->

				<!-- no common location -->
				<?php } else {
					for($i = 0; $i < count($stores); $i++) {

						// get the array of store locations for this store
						$locations = $locationsByStore[$i];
						?>
						<div class="row mt30">
							<div class="col-sm-12">
								<h3><?php echo $store->getStoreName() . ' pickup locations'; ?></h3>
							</div>
						</div>
						<div class="row mt30" id="store-<?php echo $store->getStoreId(); ?>">
							<div class="col-sm-4">
								<div class="list-group pickup-locations">
									<span class="list-group-item disabled static">
										Select a pickup location
									</span>
									<?php foreach($locations as $index => $location) {

										?>
										<a href="#" class="list-group-item <?php echo ($index === 0) ? 'active' : ''; ?>" id="location-<?php echo $location->getLocationId(); ?>">
											<?php echo $location->getLocationName() ?><br/>
											<?php echo $location->getAddress1(); ?><br/>
											<?php echo ($location->getAddress2() !== '')
												? ''
												: ''; ?>
											<?php echo $location->getCity() . ' ' . $location->getState() . ' ' .
												$location->getZipCode(); ?><br/>
										</a>
										<input type="text"
												 class="hidden"
												 id="store-<?php echo $store->getStoreId(); ?>_location-<?php echo $location->getLocationId(); ?>" />
									<?php } ?>
								</div>
							</div>
							<div class="col-sm-8">

								<!-- google map canvas -->
								<div id="map-canvas"></div>
							</div>
						</div><!-- end row -->
					<?php }
				} ?>
			</div><!-- end col-sm-12 -->
		</div><!-- end row -->

		<div class="row mt30">
			<div class="col-sm-12">
				<input type="submit" value="Continue to checkout" class="btn btn-default push-right" id="checkout-pickup-submit">
			</div>
		</div><!-- end row -->

	</form>
</div><!-- end container-fluid -->

	<!-- google maps -->
	<!--		<script src="https://maps.googleapis.com/maps/api/js"></script>-->
	<!--		<script src="--><?php //echo $prefix; ?><!--js/google-maps.js"></script>-->
<script src="../js/checkout-pickup.js"></script>

<?php require_once('../php/lib/footer.php'); ?>