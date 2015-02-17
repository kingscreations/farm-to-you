

	<?php
	session_start();
	$currentDir = dirname(__FILE__);
	//require_once '../../root-path.php';
	//require_once '../stripe-api/header.php';
	//require_once("../../store/index.php");
	require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
	require_once("../classes/store.php");
	require_once("../classes/location.php");
	require_once("../classes/storelocation.php");
	require_once("../classes/profile.php");
	require_once("../classes/user.php");
	require_once('../../dummy-session.php');


	?>

	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/2.7.5/idangerous.swiper.min.css"/>
	<link rel="stylesheet" href="../../css/main.css"/>


	<?php

	// verify the form values have been submitted
	if(@isset($_POST["locationName"]) === false || @isset($_POST["address1"]) === false || @isset($_POST["city"]) === false || @isset($_POST["zipCode"]) === false || @isset($_POST["state"]) === false){
		echo "<p class=\"alert alert-danger\">Form values not complete. Verify the form and try again.</p>";
	}

	try {
//
		mysqli_report(MYSQLI_REPORT_STRICT);
		$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
		$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

		$storeName = $_SESSION['store']['name'];

		$storeId = $_SESSION['store']['id'];

		if(@isset($_POST["locationName"]) && ($_POST["address1"]) && ($_POST["address2"]) && ($_POST["zipCode"]) && ($_POST["city"]) && ($_POST["state"]) && ($_POST["country"])) {
			$location = new Location(null, $_POST["locationName"], $_POST["country"], $_POST["state"], $_POST["city"], $_POST["zipCode"], $_POST["address1"], $_POST["address2"]);
		} else if(@isset($_POST["locationName"]) && ($_POST["address1"]) && ($_POST["zipCode"]) && ($_POST["city"]) && ($_POST["state"]) && ($_POST["country"])) {
			$location = new Location(null, $_POST["locationName"], $_POST["country"], $_POST["state"], $_POST["city"], $_POST["zipCode"], $_POST["address1"], null);
		} else if(@isset($_POST["locationName"]) && ($_POST["address1"]) && ($_POST["address2"]) && ($_POST["zipCode"]) && ($_POST["city"]) && ($_POST["state"])) {
			$location = new Location(null, $_POST["locationName"], null, $_POST["state"], $_POST["city"], $_POST["zipCode"], $_POST["address1"], $_POST["address2"]);
		} else {
			$location = new Location(null, $_POST["locationName"], null, $_POST["state"], $_POST["city"], $_POST["zipCode"], $_POST["address1"], null);
		}
		$location->insert($mysqli);
		$locationId = $location->getLocationId();
		$storeLocation = new StoreLocation($storeId, $locationId);
		$storeLocation->insert($mysqli);



		echo "<p class=\"alert alert-success\">" . $location->getLocationName() . " added!</p>";

	?>

	<div class="row-fluid">
	<div class="col-sm-12">
	<h3><strong><?php echo $storeName ?></strong>
		<h2>Add Location</h2>
		<form class="form-inline" id="locationController" method="post" action="location-controller.php">

		<?php require_once('../../location/index.php') ?>
		<form class="form-inline" id="done" method="post" action="../../store/index.php">
			<button type="submit">Done</button>

		</form>

<?php
	} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";?>
	<form class="form-inline" id="back" method="post" action="../../store/index.php">
		<button type="submit">Back</button>
	</form>
<?php }
	require_once "../lib/footer.php";?>