<?php
$currentDir = dirname(__FILE__);

//require_once("../dummy-session-single.php");
require_once("../root-path.php");
require_once("../php/lib/header.php");
//var_dump($_SESSION);

// classes
require_once("../php/classes/location.php");

?>

<script src="../js/add-location.js"></script>

<div class="row-fluid">
	<div class="col-sm-12">
		<h2>Add Location</h2>
			<form class="form-inline" id="locationController" method="post" action="../php/forms/add-location-controller.php">
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
	</div>
</div>

<?php require_once "../php/lib/footer.php"; ?>