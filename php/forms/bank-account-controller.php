<?php
session_start();
$currentDir = dirname(__FILE__);
require_once("../classes/profile.php");
require_once '/etc/apache2/capstone-mysql/encrypted-config.php';

//$profileId = $_SESSION['profileId'];
?>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<?php


	try {
		//
		mysqli_report(MYSQLI_REPORT_STRICT);
		$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
		$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

//$profileId = $_SESSION['profileId'];

		$profileId = 3;

//	$userId = $_SESSION['user']['id'];

		$userId = 16;

		/**
		 * Stripe API calls
		 */

// auto load includes all the API files
		require_once('../../external-libs/autoload.php');

// setup
		\Stripe\Stripe::setApiKey($configArray['stripe']);
		$error = '';
		$success = '';


		$profile = Profile::getProfileByProfileId($mysqli, $profileId);
		$profileType = $profile->getProfileType();
		$profileFirstname = $profile->getFirstName();
		$profileLastname = $profile->getLastName();
		$profilePhone = $profile->getPhone();
		$profileImage = $profile->getImagePath();
		$profileToken = $profile->getCustomerToken();

		$country = $_POST['country'];
		$bankAcct = $_POST['bank-acct'];
		$routingNmb = $_POST['routing-nmb'];

		$name = $profileFirstname . ' ' . $profileLastname;

		//check if they have a token with an rp id already
		if((preg_match('/^rp/', $profileToken) === 0)) {
			// create bank account associative array
			$bankAccount = array(
				"country" => $country,
				"routing_number" => $routingNmb,
				"account_number" => $bankAcct
			);


// Create a Recipient

			$recipient = \Stripe\Recipient::create(array(
					"name" => $name,
					"type" => "individual",
					"bank_account" => $bankAccount,)
			);

			if($recipient->id !== '') {
				$profileToken = $recipient->id;
				$profile->setCustomerToken($profileToken);
			}

			$profile->update($mysqli);
		} else {
			echo "Bank account already added.";
		}

			echo "<p class=\"alert alert-success\">Profile (id = " . $profile->getProfileId() . ") updated!</p>";
		} catch (Exception $exception) {
			echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
		}



?>

