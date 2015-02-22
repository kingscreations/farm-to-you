<?php

$currentDir = dirname(__FILE__);
//require_once("../../dummy-session-single.php");
require_once ("../../root-path.php");

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/store.php");
require_once("../classes/location.php");
require_once("../classes/storelocation.php");
require_once("../classes/profile.php");
require_once("../classes/user.php");
require_once("../lib/utils.php");


try {

	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$store = Store::getStoreByStoreId($mysqli, 1);
//	var_dump($store);
	$storeName = $store->getStoreName();
	$storeDescription = $store->getStoreDescription();
	$storeImagePath = $store->getImagePath();
	$storeId = $store->getStoreId();

	if($_POST['editStoreName'] !== '') {
		$storeName = $_POST['editStoreName'];
//		$_SESSION['store'] ['name'] = $_POST['editStoreName'];
		$store->setStoreName($storeName);
	}

	if ($_POST['editStoreDescription'] !== ''){
		$storeDescription = $_POST['editStoreDescription'];
//		$_SESSION['store'] ['description'] = $_POST['editStoreDescription'];
		$store->setStoreDescription($storeDescription);
	} else {
		$storeDescription = '';
		$store->setStoreDescription($storeDescription);
	}

	if(@isset($_FILES['editInputImage']) === true) {
		$imageBasePath = '/var/www/html/farm-to-you/images/store/';
		$imageExtension = checkInputImage($_FILES['editInputImage']);
		$imageFileName = $imageBasePath . 'store-' . $storeId . '.' . $imageExtension;
		$store->setImagePath($imageFileName);
		move_uploaded_file($_FILES['editInputImage']['tmp_name'], $imageFileName);
	}
	$store->update($mysqli);

	echo "<p class=\"alert alert-success\">" . $store->getStoreName() . " updated!</p>";

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";?>
	<!--<form class="form-inline" id="back" method="post" action="../../store/index.php">-->
	<!--	<button type="submit">Back</button>-->
	<!--</form>-->
<?php }
