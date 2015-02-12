<?php
//$currentDir = dirname(__FILE__);
//require_once '../../root-path.php';
//require_once '../lib/header.php';
//require_once("../../store/index.php");
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

function getRandomWord($len = 10) {
	$word = array_merge(range('a', 'z'), range('A', 'Z'));
	shuffle($word);
	return substr(implode($word), 0, $len);
}
$randActivation = bin2hex(openssl_random_pseudo_bytes(8));
$randSalt = bin2hex(openssl_random_pseudo_bytes(16));
$randHash = bin2hex(openssl_random_pseudo_bytes(64));

try {
//
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$user = new User(null, getRandomWord() . "@test.com", $randHash, $randSalt, $randActivation);
	$user->insert($mysqli);
	$profile = new Profile(null, "Test", "Test2", "5555555555", "m", "012345", "http://www.cats.com/cat.jpg", $user->getUserId());
	$profile->insert($mysqli);

	if(@isset($_POST["InputImage"]) && ($_POST["storeDescription"])) {
		$store = new Store(null, $profile->getProfileId(), $_POST["storeName"], $_POST["InputImage"], null, $_POST["storeDescription"]);
	} else if(@isset($_POST["InputImage"])) {
		$store = new Store(null, $profile->getProfileId(), $_POST["storeName"], $_POST["InputImage"], null, null);
	} else if(@isset($_POST["storeDescription"])) {
		$store = new Store(null, $profile->getProfileId(), $_POST["storeName"], null, null, $_POST["storeDescription"]);
	} else {
		$store = new Store(null, $profile->getProfileId(), $_POST["storeName"], null, null, null);
	}

	$store->insert($mysqli);


	echo "<p class=\"alert alert-success\">" . $store->getStoreName() . " added!</p>";
//	$mysqlStore = Store::getStoreByProfileId($mysqli, $profile->getProfileId());
//	var_dump($mysqlStore);
//	$listOfStores = (string)$mysqlStore;
//	echo $listOfStores;
	?>
	<div class="row-fluid">
	<div class="col-sm-12">
	<h3><strong><?php echo $store->getStoreName() ?></strong></h3>
	<h2>Add Location</h2>
	<form class="form-inline" id="locationController" method="post" action="location-controller.php">

	<?php require_once('../../location/index.php') ?>
<?php
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";?>
<form class="form-inline" id="back" method="post" action="../../store/index.php">
	<button type="submit">Back</button>
</form>
<?php } ?>