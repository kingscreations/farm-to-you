<?php
/**
 * @author Alonso Indacochea <alonso@hermesdevelopment.com>
 */

// header
$currentDir = dirname(__FILE__);
require_once("../root-path.php");
require_once("../php/lib/header.php");

// classes
require_once("../php/classes/location.php");

?>

	<!--js validation + ajax call-->
<script src="../js/add-location.js"></script>

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
		<h2>Add Location</h2>
		<form class="form-inline" id="locationController" method="post" action="../php/forms/add-location-controller.php">
			<?php echo generateInputTags(); ?>
			<div class="form-group">
				<label for="locationName">Location Name</label>
				<input type="text" id="locationName" name="locationName" value="Home">
			</div>
			<br>
			<div class="form-group">
				<label for="address1">Address</label>
				<input type="text" id="address1" name="address1" value="1228 W La Entrada">
			</div>
			<br>
			<div class="form-group">
				<label for="address2"></label>
				<input type="text" id="address2" name="address2">
			</div>
			<br>
			<div class="form-group">
				<label for="city">City</label>
				<input type="text" id="city" name="city" value="Corrales">
			</div>
			<div class="form-group">
				<label for="state">State</label>
				<input type="text" id="state" name="state" value="NM">
			</div>
			<div class="form-group">
				<label for="zipCode">Zip Code</label>
				<input type="text" id="zipCode" name="zipCode" value="87048">
			</div>
			<div class="form-group">
				<label for="country">Country</label>
				<input type="text" id="country" name="country">
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