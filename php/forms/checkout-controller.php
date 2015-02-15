<?php

/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

// start session as the first statement
session_start();
//var_dump($_SESSION);

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// model
require_once("../classes/checkout.php");
require_once("../classes/orderproduct.php");
require_once("../classes/order.php");
require_once("../classes/profile.php");


// stripe API
require_once 'external-libs/autoload.php';


//Stripe::setApiKey("pk_test_jhr3CTTUfUhZceoZrxs5Hpu0");
//$error = '';
//$success = '';
//
//if($_POST) {
//
//	if(!@isset($_POST['stripeToken'])) {
//		throw new Exception("The Stripe Token was not generated correctly");
//	}
//
//	$stripeToken = escapeshellcmd(filter_var($_POST['stripeToken'], FILTER_SANITIZE_STRING));
//
//try {
//	$charge = Stripe_Charge::create(array(
//			"amount" => 1000, // amount in cents, again
//			"currency" => "usd",
//			"card" => $stripeToken,
//			"description" => "payinguser@example.com")
//	);
//} catch(Stripe_CardError $e) {
//	// The card has been declined
//}
//}

try {
	mysqli_report(MYSQLI_REPORT_STRICT);

	// get the credentials information from the server
	$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
	$configArray = readConfig($configFile);

	// connection
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

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

	$checkout = new Checkout($order->getOrderId(), new DateTime(), )

	$mysqli->close();

	echo '<p class=\"alert alert-success\">Your payment was successful.</p>';

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>