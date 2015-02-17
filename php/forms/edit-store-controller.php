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
	$storeId = $_SESSION['store'] ['id'];
	$storeName = $_SESSION['store'] ['name'];
	$storeImagePath = $_SESSION['store'] ['image'];
	$storeDescription = $_SESSION['store'] ['description'];
	$storeCreationDate = $_SESSION['store'] ['creation'];


	if($_POST['editStoreName'] !== '' && $_POST['editStoreDescription'] !== '' && $_POST["editInputImage"] !== '') {
		$store = new Store($storeId, $profileId, $_POST['editStoreName'], $_POST["editInputImage"], $storeCreationDate, $_POST['editStoreDescription']);
	} else if($_POST['editStoreName'] === '' && $_POST['editStoreDescription'] !== '' && $_POST["editInputImage"] !== '') {
		$store = new Store($storeId, $profileId, $storeName, $_POST["editInputImage"], $storeCreationDate, $_POST['editStoreDescription']);
	} else if($_POST['editStoreName'] === '' && $_POST['editStoreDescription'] === '' && $_POST['editInputImage'] !== '') {
		$store = new Store($storeId, $profileId, $storeName, $_POST["editInputImage"], $storeCreationDate, $storeDescription);
	} else if($_POST['editStoreName'] !== '' && $_POST['editStoreDescription'] === '' && $_POST["editInputImage"] !== '') {
		$store = new Store($storeId, $profileId, $_POST['editStoreName'], $_POST["editInputImage"], $storeCreationDate, $storeDescription);
	} else if($_POST['editStoreName'] === '' && $_POST['editStoreDescription'] !== '' && $_POST["editInputImage"] === '') {
		$store = new Store($storeId, $profileId, $storeName, $storeImagePath, $storeCreationDate, $_POST['editStoreDescription']);
	} else if($_POST['editStoreName'] !== '' && $_POST['editStoreDescription'] === '' && $_POST["editInputImage"] === '') {
		$store = new Store($storeId, $profileId, $_POST['editStoreName'], $storeImagePath, $storeCreationDate, $storeDescription);
	} else if($_POST['editStoreName'] !== '' && $_POST['editStoreDescription'] !== '' && $_POST["editInputImage"] === '') {
		$store = new Store($storeId, $profileId, $_POST['editStoreName'], $storeImagePath, $storeCreationDate, $_POST['editStoreDescription']);
	} else {
		$store = new Store($storeId, $profileId, $storeName, $storeImagePath, $storeCreationDate, $storeDescription);
	}

	$store->update($mysqli);

	echo "<p class=\"alert alert-success\">" . $store->getStoreName() . " updated!</p><br><p class=\"alert alert-success\">";

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";?>
	<!--<form class="form-inline" id="back" method="post" action="../../store/index.php">-->
	<!--	<button type="submit">Back</button>-->
	<!--</form>-->
<?php }
