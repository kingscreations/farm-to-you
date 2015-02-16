<?php

/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

// start session as the first statement
session_start();

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// model
require_once("../classes/checkout.php");
require_once("../classes/orderproduct.php");
require_once("../classes/product.php");
require_once("../classes/order.php");
require_once("../classes/profile.php");
require_once("../classes/user.php");

//
if(!@isset($_POST['stripeToken'])) {
	throw new Exception("The Stripe Token was not generated correctly");
}

if(!@isset($_POST['rememberUser'])) {
	throw new Exception("remember my card information is not valid");
}

try {
	mysqli_report(MYSQLI_REPORT_STRICT);

	// get the credentials information from the server
	$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
	$configArray = readConfig($configFile);

	// connection
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

//	var_dump($_SESSION);
	$user = User::getUserByUserId($mysqli, $_SESSION['user']['id']);
	$profile = Profile::getProfileByProfileId($mysqli, $_SESSION['profile']['id']);

	if($profile === null) {
		echo '<p class=\"alert alert-danger\">Internal server error.</p>';
	}

	// TODO: only if the customer check the appropriate check box:
//	$profile->setCustomerToken();

	// create and insert a new order
	$order = new Order(null, $_SESSION['profile']['id'], new DateTime());
	$order->insert($mysqli);

	$count = 1;
	$totalPrice = 0.0;
	foreach($_SESSION['products'] as $sessionProduct) {
		$product = Product::getProductByProductId($mysqli, $sessionProduct['id']);

		// create and insert a new order product
		$orderProduct = new OrderProduct($order->getOrderId(), $product->getProductId(), $sessionProduct['quantity']);
		$orderProduct->insert($mysqli);

		// calculate the final price (per product) and the total order price
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

		$count++;
	}

	// create and insert the checkout with the current date and the total price
	$checkout = new Checkout(null, $order->getOrderId(), new DateTime(), $totalPrice);
	$checkout->insert($mysqli);

	clearDatabase($mysqli);
	$mysqli->close();

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

// stripe API
require_once '../external-libs/stripe-api/stripe.php';

Stripe::setApiKey("pk_test_jhr3CTTUfUhZceoZrxs5Hpu0");
$error = '';
$success = '';

$stripeToken = escapeshellcmd(filter_var($_POST['stripeToken'], FILTER_SANITIZE_STRING));
$rememberUser = escapeshellcmd(filter_var($_POST['rememberUser'], FILTER_SANITIZE_STRING));

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