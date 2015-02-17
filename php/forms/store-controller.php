<?php
session_start();

$currentDir = dirname(__FILE__);
require_once("../../dummy-session.php");
require_once ("../../root-path.php");

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/store.php");
require_once("../classes/location.php");
require_once("../classes/storelocation.php");
require_once("../classes/profile.php");
require_once("../classes/user.php");


// verify the form values have been submitted
//if(@isset($_POST["storeName"]) === false) {
//	echo "<p class=\"alert alert-danger\">Form values not complete. Verify the form and try again.</p>";
//}

try {

	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$profileId = $_SESSION['profile']['id'];

	if(!@isset($_POST["locationName"]) || !@isset($_POST["address1"]) ||
		!@isset($_POST["zipCode"]) || !@isset($_POST["city"]) || !@isset($_POST["state"]) || !@isset($_POST["storeName"])) {
		throw new Exception('missing a required field');
	}

	if($_POST['address2'] !== '' && $_POST['country'] !== '' && $_POST["inputImage"] !== '' && $_POST["storeDescription"]) {
		$location = new Location(null, $_POST["locationName"], $_POST["country"], $_POST["state"], $_POST["city"],
			$_POST["zipCode"], $_POST["address1"], $_POST["address2"]);
		$store = new Store(null, $profileId, $_POST["storeName"], $_POST["inputImage"], null, $_POST["storeDescription"]);
	} else if($_POST['address2'] === '' && $_POST['country'] === '' && $_POST["inputImage"] === '') {
		$location = new Location(null, $_POST["locationName"], null, $_POST["state"], $_POST["city"],
			$_POST["zipCode"], $_POST["address1"], null);
		$store = new Store(null, $profileId, $_POST["storeName"], null, null, $_POST["storeDescription"]);
	} else if($_POST['address2'] === '' && $_POST['country'] === '' && $_POST["storeDescription"] === '') {
		$location = new Location(null, $_POST["locationName"], null, $_POST["state"], $_POST["city"],
			$_POST["zipCode"], $_POST["address1"], null);
		$store = new Store(null, $profileId, $_POST["storeName"], $_POST["inputImage"], null, null);
	} else if($_POST['address2'] === '' && $_POST["storeDescription"] === '') {
		$location = new Location(null, $_POST["locationName"], $_POST["country"], $_POST["state"], $_POST["city"],
			$_POST["zipCode"], $_POST["address1"], null);
		$store = new Store(null, $profileId, $_POST["storeName"], $_POST["inputImage"], null, null);
	} else if($_POST['country'] === '' && $_POST["storeDescription"] === '') {
		$location = new Location(null, $_POST["locationName"], null, $_POST["state"], $_POST["city"],
			$_POST["zipCode"], $_POST["address1"], $_POST["address2"]);
		$store = new Store(null, $profileId, $_POST["storeName"], $_POST["inputImage"], null, null);
	} else if($_POST['address2'] === '' && $_POST["inputImage"] === '') {
		$location = new Location(null, $_POST["locationName"], $_POST["country"], $_POST["state"], $_POST["city"],
			$_POST["zipCode"], $_POST["address1"], null);
		$store = new Store(null, $profileId, $_POST["storeName"], null, null, $_POST["storeDescription"]);
	} else if($_POST['country'] === '' && $_POST["inputImage"] === '') {
		$location = new Location(null, $_POST["locationName"], null, $_POST["state"], $_POST["city"],
			$_POST["zipCode"], $_POST["address1"], $_POST["address2"]);
		$store = new Store(null, $profileId, $_POST["storeName"], null, null, $_POST["storeDescription"]);
	} else if($_POST['address2'] === '') {
		$location = new Location(null, $_POST["locationName"], $_POST["country"], $_POST["state"], $_POST["city"],
			$_POST["zipCode"], $_POST["address1"], null);
		$store = new Store(null, $profileId, $_POST["storeName"], $_POST["inputImage"], null, $_POST["storeDescription"]);
	} else if($_POST['country'] === '') {
		$location = new Location(null, $_POST["locationName"], null, $_POST["state"], $_POST["city"],
			$_POST["zipCode"], $_POST["address1"], $_POST["address2"]);
		$store = new Store(null, $profileId, $_POST["storeName"], $_POST["inputImage"], null, $_POST["storeDescription"]);
	} else {
		$location = new Location(null, $_POST["locationName"], null, $_POST["state"], $_POST["city"], $_POST["zipCode"], $_POST["address1"], null);
		$store = new Store(null, $profileId, $_POST["storeName"], null, null, null);
	}


	$store->insert($mysqli);
	$storeId = $store->getStoreId();
	$location->insert($mysqli);
	$locationId = $location->getLocationId();
	$storeLocation = new StoreLocation($storeId, $locationId);
	$storeLocation->insert($mysqli);

	$storeNames = Store::getAllStoresByProfileId($mysqli, $store->getProfileId());



	$_SESSION['store'] = array(
		'id' 				=> $store->getStoreId(),
		'name'			=> $store->getStoreName(),
		'description'	=> $store->getStoreDescription(),
		'image'			=> $store->getImagePath(),
		'creation'		=> $store->getCreationDate()
	);

	echo "<p class=\"alert alert-success\">" . $store->getStoreName() . " added!</p><br><p class=\"alert alert-success\">" . $location->getLocationName() . " added!</p>";

	?>
	<div class="row-fluid">
	<div class="col-sm-12">
	<h3><strong><?php echo $_SESSION['store']['name']; ?></strong>
	<br>

	<a href="../edit-store/index.php" class="btn btn-info" role="button">Edit Store</a>	<br>


<?php
	} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}
?>