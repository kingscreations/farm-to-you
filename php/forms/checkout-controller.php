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
require_once("../classes/location.php");

// connection configuration
mysqli_report(MYSQLI_REPORT_STRICT);

// get the credentials information from the server
$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";

$_SESSION['location'] = array(
	id, id2, id3
);

try {
	// connection
	$configArray = readConfig($configFile);
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	$user = User::getUserByUserId($mysqli, $_SESSION['user']['id']);
	$profile = Profile::getProfileByProfileId($mysqli, $_SESSION['profile']['id']);

	if($profile === null) {
		echo '<p class=\"alert alert-danger\">Internal server error.</p>';
	}

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

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

/**
 * Stripe API calls
 */

// auto load includes all the API files
require_once('../../external-libs/autoload.php');

// setup
\Stripe\Stripe::setApiKey($configArray['stripe']);
$error = '';
$success = '';

// Convert the price in dollars to a price in cents
// and from float to integer to be compatible with Stripe API
$totalPrice = intval($totalPrice * 100);

try {
	if(@isset($_POST['creditCard']) === true && $_POST['creditCard'] === 'old') {
		$customerToken = $profile->getCustomerToken();

		if($customerToken !== null && strpos($customerToken, 'cus_') !== false) {

			// charge the customer with the memorize information
			\Stripe\Charge::create(array(
					"amount"   => $totalPrice, // amount in cents
					"currency" => "usd",
					"customer" => $customerToken)
			);

		} else {
			throw new Exception('Custom token from Profile invalid');
		}

	} else {

		// check the post variable
		if(!@isset($_POST['stripeToken'])) {
			throw new Exception("The Stripe Token was not generated correctly");
		}

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

		if($rememberUser === true) {

			// first create a new customer
			$customer = \Stripe\Customer::create(array(
				"card" => $stripeToken,
				"description" => $user->getEmail()
			));

			// then charge the new customer
			\Stripe\Charge::create(array(
				"amount" => $totalPrice, // amount in cents
				"currency" => "usd",
				"customer" => $customer->id
			));
			// then save the customer info to the profile
			$profile->setCustomerToken($customer->id);
			$profile->update($mysqli);
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
		}
	}

	echo "<p class=\"alert alert-success\">Payment done.</p>";

	//close the database connection
	$mysqli->close();

} catch(Stripe_CardError $stripeException) {
	// The card has been declined
	echo "<p class=\"alert alert-danger\">Exception: " . $stripeException->getMessage() . "</p>";
}




/**
 * Temporary function which acts like a tear down method
 *
 * @param $mysqli the database connection
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