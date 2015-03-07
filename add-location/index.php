<?php
/**
 * @author Alonso Indacochea <alonso@hermesdevelopment.com>
 */


// header
$currentDir = dirname(__FILE__);
require_once("../root-path.php");
require_once("../php/lib/header.php");

if($_SESSION['storeId'] === null) {
	header('Location: ../sign-in/index.php');
	exit();
}

// classes
require_once("../php/classes/location.php");

?>

	<!--js validation + ajax call-->
<script src="../js/add-location.js"></script>
<div>
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
</div>
<div class="row-fluid">
	<div class="col-sm-9">
		<form class="form-inline transparent-form" id="locationController" method="post" action="../php/forms/add-location-controller.php">
			<h2>Add Location</h2>
			<?php echo generateInputTags(); ?>
			<div class="form-group">
				<label for="locationName">Location Name</label>
				<input type="text" id="locationName" name="locationName">
			</div>
			<br>
			<div class="form-group">
				<label for="address1">Address</label>
				<input type="text" id="address1" name="address1">
			</div>
			<br>
			<div class="form-group">
				<label for="address2">Address Cont.</label>
				<input type="text" id="address2" name="address2">
			</div>
			<br>
			<div class="form-group">
				<label for="city">City</label>
				<input type="text" id="city" name="city" value="Albuquerque">
			</div>
			<div class="form-group">
				<label for="state">State</label>
				<input type="text" id="state" name="state" value="NM">
			</div>
			<br>
			<div class="form-group">
				<label for="zipCode">Zip Code</label>
				<input type="text" id="zipCode" name="zipCode" value="87048">
			</div>
			<div class="form-group">
				<label for="country">Country</label>
				<input type="text" id="country" name="country" value="US">
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
			<button class="btn btn-default addButton" id="back">Back</button>
		</div>
	</div>
</div>

<!--footer-->
<?php require_once "../php/lib/footer.php"; ?>