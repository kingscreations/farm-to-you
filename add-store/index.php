<?php
/**
 * @author Alonso Indacochea <alonso@hermesdevelopment.com>
 */


// header
$currentDir = dirname(__FILE__);
require_once("../root-path.php");
session_start();

if(!@isset($_SESSION['profileId'])) {
	header('Location: ../sign-in/index.php');
}

session_abort();
require_once("../php/lib/header.php");


// classes
require_once("../php/classes/store.php");

// grab profileId from session
$profileId = $_SESSION['profileId'];

?>

<div class="container-fluid container-margin-sm transparent-form user-account">
	<div class="row">

		<div id="multi-menu" class="col-md-3 hidden-xs">
			<ul class="nav nav-pills nav-stacked">
				<li><a href="../edit-profile/index.php">Edit Profile</a></li>
				<li class="active"><a href="../add-store/index.php">Manage Stores</a></li>
				<li><a href="../merchant-order-list/index.php">List of Orders</a></li>
				<li><a href="../bank-account/index.php">Bank Account</a></li>
			</ul>
		</div>
		<div class="dropdown visible-xs" style="position:relative">
			<a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Menu<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="../edit-profile/index.php">Edit Profile</a></li>
				<li class="active"><a href="../add-store/index.php">Manage Stores</a></li>
				<li><a href="../merchant-order-list/index.php">List of Orders</a></li>
				<li><a href="../bank-account/index.php">Bank Account</a></li>
			</ul>
		</div>
		<div class="col-sm-3 visible-xs">
			<h2>Add Store</h2>
		</div>
		<div class="col-sm-6">
			<form class="form-inline" id="storeController" method="post" action="../php/forms/add-store-controller.php" enctype="multipart/form-data">
				<?php echo generateInputTags(); ?>
				<div class="hidden-xs center">
					<h2>Add Store</h2>
				</div>
				<div class="form-group">
					<label for="storeName">Name</label>
					<input type="text" class="form-control" id="storeName" name="storeName" value="">
				</div>
				<br>
				<div class="form-group">
					<label for="storeDescription">Description</label>
					<textarea class="form-control" id="storeDescription" name="storeDescription"></textarea>
				</div>
				<br>
				<div class="form-group">
					<label for="inputImage">Store Image</label>
					<input type="file" class="form-control" id="inputImage" name="inputImage">
				</div>
				<br>
				<div class="form-group">
					<label for="locationName">Location Name</label>
					<input type="text" class="form-control" id="locationName" name="locationName" value="">
				</div>
				<br>
				<div class="form-group">
					<label for="address1">Address</label>
					<input type="text" class="form-control" id="address1" name="address1" value="">
				</div>
				<br>
				<div class="form-group">
					<label for="address2"></label>
					<input type="text" class="form-control" id="address2" name="address2" value="">
				</div>
				<br>
				<div class="form-group">
					<label for="city">City</label>
					<input type="text" class="form-control" id="city" name="city" value="">
				</div>
				<div class="form-group">
					<label for="state">State</label>
					<input type="text" class="form-control" id="state" name="state" value="">
				</div>
				<div class="form-group">
					<label for="zipCode">Zip Code</label>
					<input type="text" class="form-control" id="zipCode" name="zipCode" value="">
				</div>
				<div class="form-group">
					<label for="country">Country</label>
					<input type="text" class="form-control" id="country" name="country" value="">
				</div>
				<br>
				<br>
				<div class="form-group">
					<input type="submit" class="form-control" id="editSubmit" action="../php/forms/store-edit-button-controller.php" name="editSubmit" value="Submit">
				</div>
				<br>
				<br>
				<p id="outputArea"></p>
				<br>
			</form>
<?php

$currentDir = dirname(__FILE__);
require_once ("../root-path.php");

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

try {
	// get the credentials information from the server and connect to the database
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	// grab all stores by profile id in dummy session
	$stores = Store::getAllStoresByProfileId($mysqli, $profileId);

	// create table of existing stores
	if($stores !== null) {
		echo '<div class=form-group>';
		echo '<table class="table table-responsive">';
		echo '<tr>';
		echo '<th><h4>Manage your stores</h4></th>';
		echo '<th></th>';
		echo '</tr>';
		foreach($stores as $store) {
			$storeId = $store->getStoreId();
			$storeName = $store->getStoreName();
			echo '<tr>';
			echo '<td>'. $storeName . '</td>';

			echo '<td><button id="'.$storeId.'" class="btn btn-default editButton">Edit '.$storeName.' </button></td>';
			echo '</tr>';
		}
		echo '</table>';
//		echo '</div>';
		echo '</div>';
	}

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>
		</div>
	</div>
</div>

<!--js validation + ajax call-->
<script src="../js/add-store.js"></script>

<!--footer-->
<?php require_once "../php/lib/footer.php"; ?>