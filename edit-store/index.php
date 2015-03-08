<?php
/**
 * @author Alonso Indacochea <alonso@hermesdevelopment.com>
 */

// header
$currentDir = dirname(__FILE__);
require_once("../root-path.php");
require_once('../paths.php');

session_start();

if(!@isset($_SESSION['storeId'])) {
	header('Location: ../sign-in/index.php');
}

session_abort();

require_once("../php/lib/header.php");

// classes
require_once("../php/classes/store.php");
require_once("../php/classes/location.php");
require_once("../php/classes/product.php");
require_once("../php/classes/storelocation.php");

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

try {

	// get the credentials information from the server and connect to the database
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	// grab store with id from session
	$store = Store::getStoreByStoreId($mysqli, $_SESSION['storeId']);

	// create variables for attribute values
	$storeName = $store->getStoreName();
	$storeDescription = $store->getStoreDescription();
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>

	<div id="multi-menu" class="col-md-3 hidden-sm hidden-xs transparent-menu">
		<ul class="nav nav-pills nav-stacked">
			<li><a href="../edit-profile/index.php">Edit Profile</a></li>
			<li class="active"><a href="../add-store/index.php">Manage Stores</a></li>
			<li><a href="../merchant-order-list/index.php">List of Orders</a></li>
			<li><a href="../bank-account/index.php">Bank Account</a></li>
		</ul>
	</div>

	<div class="dropdown hidden-lg hidden-md" style="position:relative">
		<a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Menu<span class="caret"></span></a>
		<ul class="dropdown-menu">
			<li><a href="../edit-profile/index.php">Edit Profile</a></li>
			<li class="active"><a href="../add-store/index.php">Manage Stores</a></li>
			<li><a href="../merchant-order-list/index.php">List of Orders</a></li>
			<li><a href="../bank-account/index.php">Bank Account</a></li>
		</ul>
	</div>

	<div class="container-fluid">
	<div class="row">
	<div class="col-sm-9 transparent-form">

		<form class="form-inline transparent-form" id="editStoreController" method="post" action="../php/forms/edit-store-controller.php" enctype="multipart/form-data">
			<h2>Edit Store</h2>
			<?php echo generateInputTags(); ?>
			<div class="form-group">
				<label for="editStoreName">Store Name</label>
				<input type="text" class="form-control" name="editStoreName" id="editStoreName" value="<?php echo $storeName;?>">
			</div>
			<br>
			<div class="form-group">
				<label for="editStoreDescription">Store Description</label>
				<input type="text" class="form-control" name="editStoreDescription" id="editStoreDescription" value="<?php echo $storeDescription;?>">
			</div>
			<br>
			<div class="form-group">
				<label for="editInputImage">Image</label>
				<input type="file" class="form-control hidden" name="editInputImage" id="editInputImage">
			</div>
			<br>


			<div class="form-group edit-product">
				<a href="#" id="editStoreImageLink">
				<?php

				$baseUrl             = CONTENT_ROOT_URL . 'images/store/';
				$basePath            = CONTENT_ROOT_PATH . 'images/store/';
				$imagePlaceholderSrc = '../images/placeholder.png';
				$imageSrc            = basename($store->getImagePath());

				// show a placeholder if the product is not associated with an image
				if(is_file($basePath . $imageSrc)) {
					?>
					<img class="thumbnail img-responsive" src="<?php echo $baseUrl . $imageSrc; ?>" alt="<?php echo $storeName; ?>"/>
				<?php } else { ?>
					<img class="thumbnail img-responsive" src="<?php echo $imagePlaceholderSrc; ?>" alt="<?php echo $storeName; ?>"/>
				<?php } ?>
				</a>





			</div>
			<br>
			<div class="form-group">
					<input type="submit" class="form-control" id="editSubmit" name="editSubmit" value="Submit">
			</div>
			<br>
			<p id="outputArea"></p>
		</form>
		<br>
	</div><!-- col-sm-9 -->
	</div><!-- row -->
	</div><!-- container fluid -->


	<div class="container-fluid transparent-form">
		<div class="row">
			<div class="col-sm-9">


						<div class="form-group">
							<button id="<?php echo $_SESSION['storeId'];?>" class="btn btn-default linkStore">Link to store page</button>
						</div>
						<br>


						<div class="form-group">
							<button id="<?php echo $_SESSION['storeId'];?>" class="btn btn-default addProductButton">Add Product</button>
						</div>

						<?php


						// dummy session
						$currentDir = dirname(__FILE__);
						require_once ("../root-path.php");

						// credentials
						require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

						try {
							// get the credentials information from the server and connect to the database
							mysqli_report(MYSQLI_REPORT_STRICT);
							$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
							$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

							// grab all storeLocations by store id in dummy session
							$products = Product::getAllProductsByStoreId($mysqli, $_SESSION['storeId']);
							// create table of existing storeLocations
							if($products !== null) {

								echo '<table class="table table-responsive">';
								echo '<tr>';
								echo '<th>Products</th>';
								echo '<th></th>';
								echo '</tr>';
								foreach($products as $product) {
									$productName = $product->getProductName();
									$productId = $product->getProductId();
									echo '<tr>';
									echo '<td>'. $productName . '</td>';
									echo '<td><button id="'.$productId.'" class="btn btn-default editProductButton">Edit</button></td>';
									echo '<td><button id="'.$productId.'" class="btn btn-default deleteProductButton">Delete</button></td>';
									echo '</tr>';
								}
								echo '</table>';
							}

						} catch(Exception $exception) {
							echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
						}

						?>


						<br>
						<div class="form-group">
							<button id="<?php echo $_SESSION['storeId'];?>" class="btn btn-default addButton">Add Pick-Up Location</button>
						</div>

						<?php


						// dummy session
						$currentDir = dirname(__FILE__);
						require_once ("../root-path.php");

						// credentials
						require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

						try {
							// get the credentials information from the server and connect to the database
							mysqli_report(MYSQLI_REPORT_STRICT);
							$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
							$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

							// grab all storeLocations by store id in dummy session
							$storeLocations = StoreLocation::getAllStoreLocationsByStoreId($mysqli, $_SESSION['storeId']);
							// create table of existing storeLocations
							if($storeLocations !== null) {

								echo '<table class="table table-responsive">';
								echo '<tr>';
								echo '<th>Pick-Up Locations</th>';
								echo '<th></th>';
								echo '</tr>';
								$locationCount = 0;
								foreach($storeLocations as $storeLocation) {
									$locationId = $storeLocation->getLocationId();
									$location = Location::getLocationByLocationId($mysqli, $locationId);
									$locationName = $location->getLocationName();
									echo '<tr>';
									echo '<td>'. $locationName . '</td>';
									echo '<td><button id="'.$locationId.'" class="btn btn-default editButton">Edit</button></td>';
									if($locationCount !== 0 ) {
										echo '<td><button id="' . $locationId . '" class="btn btn-default deleteButton">Delete</button></td>';
									}
									$locationCount = 1;
									echo '</tr>';
								}
								echo '</table>';
							}

						} catch(Exception $exception) {
							echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
						}

						?>

						<br>
						<div class="form-group">
							<button class="btn btn-default addButton" id="back">Back</button>
						</div>
					</div><!-- col-sm-6 -->
		</div><!-- row -->
	</div><!-- container-fluid -->

<!--js validation + ajax call-->
<script src="../js/edit-store.js"></script>

<!--footer-->
<?php require_once("../php/lib/footer.php"); ?>

<!--$productName = $products[0]->getProductName();-->
<!--$productId = $products[0]->getProductId();-->
<!--echo '<tr>';-->
<!--	echo '<td>'. $productName . '</td>';-->
<!--	echo '<td><button id="'.$productId.'" class="btn btn-default editProductButton">Edit</button></td>';-->
<!--	echo '</tr>';-->
<!--for($i = 1; $i < count($products); $i++) {-->
<!--$productName = $products[$i]->getProductName();-->
<!--$productId = $products[$i]->getProductId();-->
<!--echo '<tr>';-->
<!--	echo '<td>'. $productName . '</td>';-->
<!--	echo '<td><button id="'.$productId.'" class="btn btn-default editProductButton">Edit</button></td>';-->
<!--	echo '<td><button id="'.$productId.'" class="btn btn-default deleteProductButton">Delete</button></td>';-->
<!--	echo '</tr>';-->
<!--}-->
<!--						-->