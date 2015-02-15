<?php

/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

// start session as the first statement
session_start();
//var_dump($_SESSION);
var_dump($_POST);

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// model
require_once("../classes/checkout.php");
require_once("../classes/orderproduct.php");
require_once("../classes/product.php");
require_once("../classes/order.php");
require_once("../classes/profile.php");
require_once("../classes/user.php");


try {
	mysqli_report(MYSQLI_REPORT_STRICT);

	// get the credentials information from the server
	$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
	$configArray = readConfig($configFile);

	// connection
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	$user = User::getUserByUserId($mysqli, $_SESSION['user']['id']);
	$profile = Profile::getProfileByProfileId($mysqli, $_SESSION['profile']['id']);

	if($profile === null) {
		echo '<p class=\"alert alert-danger\">Internal server error.</p>';
	}

	// TODO: only if the customer check the appropriate check box:
//	$profile->setCustomerToken();

	$order = new Order(null, $_SESSION['profile']['id'], new DateTime());
	$order->insert($mysqli);

	$orderProduct = new OrderProduct($order->getOrderId(), $product->getProductId(), $_POST['product'. ($count) .'Quantity']);
	$orderProduct->insert($mysqli);

	$totalPrice = 0.0;
	foreach($_SESSION['products'] as $sessionProduct) {
		$product = Product::getProductByProductId($mysqli, $sessionProduct['id']);

		$productPrice = $product->getProductPrice();
		$productPriceType = $product->getProductPriceType();
		$productWeight = $product->getProductWeight();
		$productQuantity = $sessionProduct['quantity'];

		$finalPrice = 0.0;
		if($productPriceType === 'w') {
			$finalPrice = $productPrice * $productQuantity * $productWeight;
		} else if($productPriceType === 'u') {
			$finalPrice = $productPrice * $productQuantity;
		} else {
			throw(new RangeException($productPriceType .
				' is not a valid product price type. The value should be either w or u.'));
		}
		$totalPrice = $totalPrice + $finalPrice;
	}

	// create and insert the checkout with the current date and the total price
	$checkout = new Checkout(null, $order->getOrderId(), new DateTime(), $totalPrice);
	$checkout->insert($mysqli);

	$mysqli->close();

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

// stripe API
require_once '../external-libs/stripe-api.php';

Stripe::setApiKey("pk_test_jhr3CTTUfUhZceoZrxs5Hpu0");
$error = '';
$success = '';

if($_POST) {

	if(!@isset($_POST['stripeToken'])) {
		throw new Exception("The Stripe Token was not generated correctly");
	}

	$stripeToken = escapeshellcmd(filter_var($_POST['stripeToken'], FILTER_SANITIZE_STRING));

	try {
		$charge = Stripe_Charge::create(
			array(
				"amount" => $totalPrice, // amount in cents, again
				"currency" => "usd",
				"card" => $stripeToken,
				"description" => $user->getEmail()
			)
		);
	} catch(Stripe_CardError $stripeException) {
		// The card has been declined
		echo "<p class=\"alert alert-danger\">Exception: " . $stripeException->getMessage() . "</p>";
	}
}

?>