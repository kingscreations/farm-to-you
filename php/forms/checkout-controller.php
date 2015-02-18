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

	// TODO: change the behavior according if the checkbox is checked or not

	// create and insert a new order
	$order = new Order(null, $_SESSION['profile']['id'], new DateTime());
	$order->insert($mysqli);

	$count = 1;
	$totalPrice = 0.0;
	foreach($_SESSION['products'] as $sessionProductId => $sessionProductQuantity) {
		$product = Product::getProductByProductId($mysqli, $sessionProductId);

		// create and insert a new order product
		$orderProduct = new OrderProduct($order->getOrderId(), $product->getProductId(), $sessionProductQuantity);
		$orderProduct->insert($mysqli);

		// calculate the final price (per product) and the total order price
		$productPrice = $product->getProductPrice();
		$productPriceType = $product->getProductPriceType();
		$productWeight = $product->getProductWeight();

		// TODO round the price to two digits
		$finalPrice = 0.0;
		if($productPriceType === 'w') {
			$finalPrice = $productPrice * $sessionProductQuantity * $productWeight;
		} else if($productPriceType === 'u') {
			$finalPrice = $productPrice * $sessionProductQuantity;
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

//	clearDatabase($mysqli);
	$mysqli->close();

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

// stripe API
//require_once '../external-libs/stripe-api/Stripe.php';
require_once('../../external-libs/autoload.php');

\Stripe\Stripe::setApiKey("sk_test_6bR9BBZRQppeQHGjgplRV3Bw");
$error = '';
$success = '';

// filter the stripe token
$stripeToken = filter_var($_POST['stripeToken'], FILTER_SANITIZE_STRING);

// filter the checkbox
$rememberUser = false;
if(@isset($_POST['rememberUser']) === false) {
	$rememberUser = false;
} else if($_POST['rememberUser'] === "Yes") {
	$rememberUser = true;
} else {
	throw new RangeException('Problem with the value of the remember user checkbox');
	exit();
}

// Convert the price in dollars to a price in cents
// and from float to integer to be compatible with Strip API
$totalPrice = intval($totalPrice * 100);

try {
	if($rememberUser === true) {
		$customer = \Stripe\Customer::create(array(
			"card" => $stripeToken,
			"description" => $user->getEmail()
		));

		\Stripe\Charge::create(array(
			"amount" => $totalPrice, // amount in cents
			"currency" => "usd",
			"customer" => $customer->id
		));

		// TODO saveStripeCustomerId($profile, $customer->id)
	}

	if($rememberUser === false) {

		// charge directly the user
		$charge = \Stripe\Charge::create(
			array(
				"amount" => $totalPrice, // amount in cents
				"currency" => "usd",
				"card" => $stripeToken,
				"description" => $user->getEmail()
			)
		);
		echo "<p class=\"alert alert-success\">Payment done.</p>";
	}
} catch(Stripe_CardError $stripeException) {
	// The card has been declined
	echo "<p class=\"alert alert-danger\">Exception: " . $stripeException->getMessage() . "</p>";
}


//// Save the customer ID in your database so you can use it later
//saveStripeCustomerId($user, $customer->id);
//
//// Later...
//$customerId = getStripeCustomerId($user);
//
//Stripe_Charge::create(array(
//		"amount"   => 1500, # $15.00 this time
//		"currency" => "usd",
//		"customer" => $customerId)
//);




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