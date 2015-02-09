<?php
require_once("index.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../php/classes/store.php");
require_once("../php/classes/profile.php");
require_once("../php/classes/user.php");


// verify the form values have been submitted
if(@isset($_POST["storeName"]) === false || @isset($_POST["storeDescription"]) === false) {
	echo "<p class=\"alert alert-danger\">Form values not complete. Verify the form and try again.</p>";
}

try {
//
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);
	$user = new User(null, "test@test.com", 'AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB0BC99AB10BC99AC99AB0BC99AB10BC99AB10BC99AB1010', '99AB10BC99AB10BC99AB10BC99AB10BC', '99AB10BC99AB10BC');
	$user->insert($mysqli);
	$profile = new Profile(null, "Test", "Test2", "5555555555", "m", "012345", "http://www.cats.com/cat.jpg", $user->getUserId());
	$profile->insert($mysqli);

	$store = new Store(null, $profile->getProfileId(), $_POST["storeName"], 'http://www.cats.com/cats.jpg', null, $_POST["storeDescription"]);
	$store->insert($mysqli);
	echo "<p class=\"alert alert-success\">(" . $store->getStoreName() . ") added!</p>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}
?> 