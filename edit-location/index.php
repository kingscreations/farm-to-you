<?php
/**
 * @author Alonso Indacochea <alonso@hermesdevelopment.com>
 */

// header
$currentDir = dirname(__FILE__);
require_once("../root-path.php");

session_start();

if(!@isset($_SESSION['locationId'])) {
	header('Location: ../sign-in/index.php');
}

session_abort();


require_once("../php/lib/header.php");

// classes
require_once("../php/classes/location.php");

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

try {

	// get the credentials information from the server and connect to the database
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	// grab location with id 1
	$location = Location::getLocationByLocationId($mysqli, $_SESSION['locationId']);

	// create variables for attribute values
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
	<div class="row-fluid">
		<div class="col-sm-9 transparent-form">
			<h2>Edit Location</h2>
			<form class="form-inline" id="editLocationController" method="post" action="../php/forms/edit-location-controller.php">
				<?php echo generateInputTags(); ?>
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
			</form>
			<br>

			<div class="form-group">
				<button class="btn btn-default addButton" id="back">Back</button>
			</div>
		</div>
		</div>
	</div>

<!--js validation + ajax call-->
<script src="../js/edit-location.js"></script>

<!--footer-->
<?php require_once "../php/lib/footer.php"; ?>