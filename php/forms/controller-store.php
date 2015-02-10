<?php
require_once("../../store/index.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/store.php");
require_once("../classes/profile.php");
require_once("../classes/user.php");


// verify the form values have been submitted
if(@isset($_POST["storeName"]) === false || @isset($_POST["storeDescription"]) === false || @isset($_POST["InputImage"]) === false) {
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

	$store = new Store(null, $profile->getProfileId(), $_POST["storeName"], $_POST["InputImage"], null, $_POST["storeDescription"]);
	$store->insert($mysqli);
	echo "<p class=\"alert alert-success\">(" . $store->getStoreName() . ") added!</p>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}
?> 