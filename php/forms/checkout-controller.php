<?php

/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

// start session as the first statement
session_start();

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// model

// stripe API
require_once('./lib/Stripe.php');

if($_POST) {
	Stripe::setApiKey("YOUR-API-KEY");
	$error = '';
	$success = '';

	if(!@isset($_POST['stripeToken'])) {
		throw new Exception("The Stripe Token was not generated correctly");
	}

	Stripe_Charge::create(array("amount" => 1000,
		"currency" => "usd",
		"card" => $_POST['stripeToken']));
} else {
	echo "<p class=\"alert alert-danger\">Problem with the stripe API</p>";
}

try {
	mysqli_report(MYSQLI_REPORT_STRICT);

	// get the credentials information from the server
	$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
	$configArray = readConfig($configFile);

	// connection
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	$mysqli->close();

	echo '<p class=\"alert alert-success\">Your payment was successful.</p>';

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>