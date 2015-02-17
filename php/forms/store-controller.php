<?php
session_start();

$currentDir = dirname(__FILE__);
require_once('../../dummy-session.php');
require_once '../../root-path.php';
require_once '../lib/header.php';

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/store.php");
require_once("../classes/profile.php");
require_once("../classes/user.php");

?>

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/2.7.5/idangerous.swiper.min.css"/>
<link rel="stylesheet" href="../../css/main.css"/>

<?php

// verify the form values have been submitted
if(@isset($_POST["storeName"]) === false) {
	echo "<p class=\"alert alert-danger\">Form values not complete. Verify the form and try again.</p>";
}

try {

	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$profileId = $_SESSION['profile']['id'];

	if(@isset($_POST["InputImage"]) && ($_POST["storeDescription"])) {
		$store = new Store(null, $profileId, $_POST["storeName"], $_POST["InputImage"], null, $_POST["storeDescription"]);
	} else if(@isset($_POST["InputImage"])) {
		$store = new Store(null, $profileId, $_POST["storeName"], $_POST["InputImage"], null, null);
	} else if(@isset($_POST["storeDescription"])) {
		$store = new Store(null, $profileId, $_POST["storeName"], null, null, $_POST["storeDescription"]);
	} else {
		$store = new Store(null, $profileId, $_POST["storeName"], null, null, null);
	}

	$store->insert($mysqli);
	$_SESSION['store'] = array(
		'id' 		=> $store->getStoreId(),
		'name'	=> $store->getStoreName()
	);

	echo "<p class=\"alert alert-success\">" . $store->getStoreName() . " added!</p>";

	?>
	<div class="row-fluid">
	<div class="col-sm-12">
	<h3><strong><?php echo $_SESSION['store']['name'] ?></strong></h3>
	<h2>Add Location</h2>
	<form class="form-inline" id="locationController" method="post" action="location-controller.php">

	<?php require_once('../../location/index.php') ?>
<?php
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";?>
<form class="form-inline" id="back" method="post" action="../../store/index.php">
	<button type="submit">Back</button>
</form>
<?php }
require_once "../lib/footer.php";?>