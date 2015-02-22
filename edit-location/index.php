<?php
//session_start();
$currentDir = dirname(__FILE__);

//require_once("../dummy-session-single.php");
require_once("../root-path.php");
require_once("../php/classes/location.php");
require_once("../php/lib/header.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");



try {
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$location = Location::getLocationByLocationId($mysqli, 1);

	$locationName = $location->getLocationName();
	$locationCountry = $location->getCountry();
	$locationState = $location->getState();
	$locationCity = $location->getCity();
	$locationZipCode = $location->getZipCode();
	$locationAddress1 = $location->getAddress1();
	$locationAddress2 = $location->getAddress2();

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>

	<script src="../js/edit-location.js"></script>

	<div class="row-fluid">
		<div class="col-sm-12">
			<h2>Edit Location</h2>

			<form class="form-inline" id="editLocationController" method="post" action="../php/forms/edit-location-controller.php">

				<div class="form-group">
					<label for="locationName">Location Name</label>
					<input type="text" class="form-control" name="locationName" id="locationName" value="<?php echo $locationName;?>">
				</div>

				<br>

				<div class="form-group">
					<label for="address1">Address</label>
					<input type="text" class="form-control" id="address1" name="address1" value="<?php echo $locationAddress1;?>">
				</div>
				<br>

				<div class="form-group">
					<label for="address2"></label>
					<input type="text" class="form-control" id="address2" name="address2" value="<?php echo $locationAddress2;?>">
				</div>
				<br>

				<div class="form-group">
					<label for="city">City</label>
					<input type="text" class="form-control" id="city" name="city" value="<?php echo $locationCity;?>">
				</div>
				<div class="form-group">
					<label for="state">State</label>
					<input type="text" class="form-control" id="state" name="state" value="<?php echo $locationState;?>">
				</div>
				<div class="form-group">
					<label for="zipCode">Zip Code</label>
					<input type="text" class="form-control" id="zipCode" name="zipCode" value="<?php echo $locationZipCode;?>">
				</div>
				<div class="form-group">
					<label for="country">Country</label>
					<input type="text" class="form-control" id="country" name="country" value="<?php echo $locationCountry;?>">
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
			<br>
		</div>
	</div>
<?php


require_once "../php/lib/footer.php";

?>