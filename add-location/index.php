<?php
/**
 * @author Alonso Indacochea <alonso@hermesdevelopment.com>
 */

session_start();

if(!@isset($_SESSION['storeId'])) {
	header('Location: ../sign-in/index.php');
}

session_abort();

// header
$currentDir = dirname(__FILE__);
require_once("../root-path.php");
require_once("../php/lib/header.php");

// classes
require_once("../php/classes/location.php");

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
			<h2>Add Pick-Up Location</h2>
		</div>
		<div class="col-sm-6">
		<form class="form-inline" id="locationController" method="post" action="../php/forms/add-location-controller.php">
			<?php echo generateInputTags(); ?>
			<div class="hidden-xs center">
				<h2>Add Pick-Up Location</h2>
			</div>

			<div class="form-group">
				<label for="locationName">Location Name</label>
				<input type="text" class="form-control" id="locationName" name="locationName">
			</div>
			<br>
			<div class="form-group">
				<label for="address1">Address</label>
				<input type="text" class="form-control" id="address1" name="address1">
			</div>
			<br>
			<div class="form-group">
				<label for="address2">Address Cont.</label>
				<input type="text" class="form-control" id="address2" name="address2">
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
			<br>
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
				<input type="submit" class="form-control" id="editSubmit" name="editSubmit" value="Submit">
			</div>
			<br>
			<br>
			<p id="outputArea"></p>
			<br>
		</form>
		<div class="form-group">
			<button class="btn btn-default addLocationButton" id="back">Back</button>
		</div>
	</div>
	</div>
</div>

<!--js validation + ajax call-->
<script src="../js/add-location.js"></script>

<!--footer-->
<?php require_once "../php/lib/footer.php"; ?>