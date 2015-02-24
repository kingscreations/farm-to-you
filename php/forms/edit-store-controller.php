<?php
// dummy session
session_start();
$currentDir = dirname(__FILE__);
require_once ("../../root-path.php");

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// image input processing
require_once("../lib/utils.php");

// classes
require_once("../classes/store.php");
require_once("../classes/location.php");
require_once("../classes/storelocation.php");
require_once("../classes/profile.php");
require_once("../classes/user.php");

try {

	// get the credentials information from the server and connect to the database
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	// grab store with id from session
	$store = Store::getStoreByStoreId($mysqli, $_SESSION['storeId']);

	// create variables for store attributes
	$storeName = $store->getStoreName();
	$storeDescription = $store->getStoreDescription();
	$storeImagePath = $store->getImagePath();
	$storeId = $store->getStoreId();

	// if user makes edits, update in store
	if($_POST['editStoreName'] !== '') {
		$storeName = $_POST['editStoreName'];
		$store->setStoreName($storeName);
	}

	// if user makes edits, update in store
	if ($_POST['editStoreDescription'] !== ''){
		$storeDescription = $_POST['editStoreDescription'];
		$store->setStoreDescription($storeDescription);
	// else, if user leaves field empty, delete description and update store
	} else {
		$storeDescription = '';
		$store->setStoreDescription($storeDescription);
	}

	// if user makes edits, update in store and upload image
	if(@isset($_FILES['editInputImage']) === true) {
		$imageBasePath = '/var/www/html/farm-to-you/images/store/';
		$imageExtension = checkInputImage($_FILES['editInputImage']);
		$imageFileName = $imageBasePath . 'store-' . $storeId . '.' . $imageExtension;
		$store->setImagePath($imageFileName);
		move_uploaded_file($_FILES['editInputImage']['tmp_name'], $imageFileName);
	}

	// update store in database
	$store->update($mysqli);

	echo "<p class=\"alert alert-success\">" . $store->getStoreName() . " updated!</p>";

} catch(Exception $exception) {

	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";?>
<?php }
