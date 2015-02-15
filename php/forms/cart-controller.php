<?php

session_start();
//ob_start();

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

require_once("../classes/orderproduct.php");
require_once("../classes/order.php");

try {
	mysqli_report(MYSQLI_REPORT_STRICT);

	// get the credentials information from the server
	$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
	$configArray = readConfig($configFile);

	// connection
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	/**
	 * submit form call
	 */
	for($i = 0; $i < count($_POST); $i++) {
		if(@isset($_POST['product'. ($i + 1) .'Quantity']) === false) {
			echo "<p class=\"alert alert-danger\">form values not complete. Verify the form and try again.</p>";
		}
	}

	$count = 1;
	foreach($_SESSION['products'] as $productFromSession) {

		// get the product from the database
		$product = Product::getProductByProductId($mysqli, $productFromSession['id']);

		$order = new Order(null, $_SESSION['profile']['id'], new DateTime());
		$order->insert($mysqli);

		$orderProduct = new OrderProduct($order->getOrderId(), $product->getProductId(), $_POST['product'. ($count) .'Quantity']);
		$orderProduct->insert($mysqli);

		$count++;
	}

	clearDatabase($mysqli);
	$mysqli->close();

	// TODO: for test purpose -> get rid of that as soon as possible
	echo "<p class=\"alert alert-success\">Order (id = " . $order->getOrderId() . ") done!</p>";
//	header("Location: https://bootcamp-coders.cnm.edu/~fgoussin/farm-to-you/checkout-shipping/");
//	die();

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

//ob_end_flush();

/**
 * Temporary function which acts like a tear down method
 *
 * @param $mysqli the database connexion
 */
function clearDatabase($mysqli) {
	$orderProducts = OrderProduct::getAllOrderProducts($mysqli);
	if($orderProducts !== null) {
		foreach($orderProducts as $orderProduct) {
			$orderProduct->delete($mysqli);
		}
	}

	$products = Product::getAllProducts($mysqli);
	if($products !== null) {
		foreach($products as $product) {
			$product->delete($mysqli);
		}
	}

	$orders = Order::getAllOrders($mysqli);
	if($orders !== null) {
		foreach($orders as $order) {
			$order->delete($mysqli);
		}
	}

	$profiles = Profile::getAllProfiles($mysqli);
	if($profiles !== null) {
		foreach($profiles as $profile) {
			$profile->delete($mysqli);
		}
	}

	$users = User::getAllUsers($mysqli);
	if($users !== null) {
		foreach($users as $user) {
			$user->delete($mysqli);
		}
	}
}

?>