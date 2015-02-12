<?php

session_start();

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");


require_once("../classes/orderproduct.php");
require_once("../classes/order.php");
require_once("../classes/profile.php");
require_once("../classes/user.php");

for($i = 0; $i < count($_POST); $i++) {
	if(@isset($_POST['product'. ($i + 1) .'Quantity']) === false) {
		echo "<p class=\"alert alert-danger\">form values not complete. Verify the form and try again.</p>";
	}
}

$products = $_SESSION['products'];

try {
	mysqli_report(MYSQLI_REPORT_STRICT);

	// get the credentials information from the server
	$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
	$configArray = readConfig($configFile);

	// connection
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	$count = 1;
	foreach($products as $product) {
		$profileId = $count; // placeholder

		$order = new Order(null, 12, new DateTime());
		$order->insert($mysqli);

		$orderProduct = new OrderProduct($order->getOrderId(), $product['productId'], $_POST['product'. ($count) .'Quantity']);
		$orderProduct->insert($mysqli);

		$count++;
	}

	$mysqli->close();
	echo "<p class=\"alert alert-success\">Tweet (id = " . $tweet->getTweetId() . ") posted!</p>";

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

function deleteAllFromDataBase($mysqli) {
	$users = User::getAllUsers($mysqli);
	foreach($users as $user) {
		$user->delete($mysqli);
	}

	$profiles = Profile::getAllUsers($mysqli);
	foreach($profiles as $profile) {
		$profile->delete($mysqli);
	}

	$products = Product::getAllUsers($mysqli);
	foreach($products as $product) {
		$product->delete($mysqli);
	}
}

?>