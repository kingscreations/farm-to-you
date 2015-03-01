<?php
/**
 * @author Alonso Indacochea <alonso@hermesdevelopment.com>
 */

// header
$currentDir = dirname(__FILE__);
require_once("../root-path.php");
require_once('../paths.php');
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

<!--js validation + ajax call-->
<script src="../js/edit-store.js"></script>

	<div id="multi-menu" class="col-md-3 hidden-sm hidden-xs">
		<ul class="nav nav-pills nav-stacked">
			<li><a href="../edit-profile/index.php">Edit Profile</a></li>
			<li class="active"><a href="../add-store/index.php">Manage Stores</a></li>
			<li><a href="../merchant-order-list/index.php">List of Orders</a></li>
			<li class="disabled"><a href="#">Account Settings</a></li>
		</ul>
	</div>

	<div class="dropdown hidden-lg hidden-md" style="position:relative">
		<a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Menu<span class="caret"></span></a>
		<ul class="dropdown-menu">
			<li><a href="../edit-profile/index.php">Edit Profile</a></li>
			<li class="active"><a href="../add-store/index.php">Manage Stores</a></li>
			<li><a href="../merchant-order-list/index.php">List of Orders</a></li>
			<li class="disabled"><a href="#">Account Settings</a></li>
		</ul>
	</div>


<div class="row-fluid">
	<div class="col-sm-9">
		<h2>Edit Store</h2>
		<form class="form-inline" id="editStoreController" method="post" action="../php/forms/edit-store-controller.php" enctype="multipart/form-data">
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
				<input type="file" class="form-control" name="editInputImage" id="editInputImage">
			</div>
			<br>
			<div class="form-group">

				<?php

				$baseUrl             = CONTENT_ROOT_URL . 'images/store/';
				$basePath            = CONTENT_ROOT_PATH . 'images/store/';
				$imagePlaceholderSrc = 'placeholder.jpg';
				$imageSrc            = 'store-'. $_SESSION['storeId'] .'.jpg';

				// show a placeholder if the product is not associated with an image
				if(file_exists($basePath . $imageSrc)) {
					?>
					<img class="thumbnail img-responsive" src="<?php echo $baseUrl . $imageSrc; ?>" alt="<?php echo $storeName; ?>"/>
				<?php } else { ?>
					<img class="thumbnail img-responsive" src="<?php echo $baseUrl . $imagePlaceholderSrc; ?>" alt="<?php echo $storeName; ?>"/>
				<?php } ?>

			</div>
			<br>
			<div class="form-group">
					<input type="submit" class="form-control" id="editSubmit" name="editSubmit" value="Submit">
			</div>
			<br>
			<br>
			<br>
			<br>
			<p id="outputArea"></p>
		</form>


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
				foreach($storeLocations as $storeLocation) {
					$locationId = $storeLocation->getLocationId();
					$location = Location::getLocationByLocationId($mysqli, $locationId);
					$locationName = $location->getLocationName();
					echo '<tr>';
					echo '<td>'. $locationName . '</td>';
					echo '<td><button id="'.$locationId.'" class="btn btn-default editButton">Edit</button></td>';
					echo '<td><button id="'.$locationId.'" class="btn btn-default deleteButton">Delete</button></td>';
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
	</div>
</div>

<!--footer-->
<?php require_once "../php/lib/footer.php";?>