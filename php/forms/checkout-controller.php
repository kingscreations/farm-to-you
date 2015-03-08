<?php

/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

// start session as the first statement
session_start();

// model
require_once("../classes/checkout.php");
require_once("../classes/orderproduct.php");
require_once("../classes/product.php");
require_once("../classes/order.php");
require_once("../classes/profile.php");
require_once("../classes/user.php");
require_once("../classes/location.php");
require_once("../classes/storelocation.php");

// connection configuration
mysqli_report(MYSQLI_REPORT_STRICT);

// get the credentials information from the server
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";

// format the store location association nicely by creation objects
$storeLocations = [];
foreach($_SESSION['storeLocations'] as $storeLocationMap) {
	$storeLocationMapExploded = explode('|', $storeLocationMap);

	// create a new store location from the exploded string
	$storeId       = intval($storeLocationMapExploded[0]);
	$locationId    = intval($storeLocationMapExploded[1]);
	$storeLocation = new StoreLocation($storeId, $locationId);

	$storeLocations[] = $storeLocation;
}

try {
	// connection
	$configArray = readConfig($configFile);
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	$user = User::getUserByUserId($mysqli, $_SESSION['userId']);
	$profile = Profile::getProfileByProfileId($mysqli, $_SESSION['profileId']);

	if($profile === null) {
		echo '<p class=\"alert alert-danger\">Internal server error.</p>';
	}

	$count = 1;
	$totalPrice = 0.0;
	foreach($_SESSION['products'] as $sessionProductId => $sessionProduct) {
		$product = Product::getProductByProductId($mysqli, $sessionProductId);

		// calculate the final price (per product) and the total order price
		$productPrice     = $product->getProductPrice();
		$productPriceType = $product->getProductPriceType();
		$productWeight    = $product->getProductWeight();

		// calculate the total price per product
		$productTotalPrice = 0.0;
		if($productPriceType === 'w') {
			$productTotalPrice = $productPrice * $sessionProduct['quantity'] * $productWeight;
		} else if($productPriceType === 'u') {
			$productTotalPrice = $productPrice * $sessionProduct['quantity'];
		} else {
			throw(new RangeException($productPriceType .
				' is not a valid product price type. The value should be either w or u.'));
		}

		// calculate the total to charge
		$totalPrice = $totalPrice + $productTotalPrice;

		$count++;
	}

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
				"card"        => $stripeToken,
				"description" => $user->getEmail()
			));

			// then charge the new customer
			\Stripe\Charge::create(array(
				"amount"   => $totalPrice, // amount in cents
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
					"amount"      => $totalPrice, // amount in cents
					"currency"    => "usd",
					"card"        => $stripeToken,
					"description" => $user->getEmail()
				)
			);
		}
	}

	echo "<p class=\"alert alert-success\">Payment done.</p>";


	/**
	 * Create the order, the order products and the checkout objects and insert them to the database
	 */
	$order = new Order(null, $_SESSION['profileId'], new DateTime());
	$order->insert($mysqli);

	foreach($_SESSION['products'] as $sessionProductId => $sessionProduct) {
		$product = Product::getProductByProductId($mysqli, $sessionProductId);

		// search for the product related location
		$productLocationId = null;
		foreach($storeLocations as $storeLocation) {
			if($storeLocation->getStoreId() === $product->getStoreId()) {
				$productLocationId = $storeLocation->getLocationId();
				break;
			}
		}

		if($productLocationId === null) {
			throw new Exception("<p class=\"alert alert-danger\">Exception: no location found for the current product</p>");
		}

		// create and insert a new order product
		$orderProduct = new OrderProduct($order->getOrderId(), $product->getProductId(), $productLocationId, $sessionProduct['quantity']);
		$orderProduct->insert($mysqli);

		// decrement the stock limit
		// TODO rename stockLimit to stockQuantity
		$product->setStockLimit($product->getStockLimit() - $sessionProduct['quantity']);
	}

	// create and insert the checkout with the current date and the total price
	$checkout = new Checkout(null, $order->getOrderId(), new DateTime(), $totalPrice / 100);
	$checkout->insert($mysqli);

	//close the database connection
	$mysqli->close();

} catch(Stripe_CardError $stripeException) {
	// The card has been declined
	echo "<p class=\"alert alert-danger\">Exception: " . $stripeException->getMessage() . "</p>";
}

?>