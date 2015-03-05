<?php

$currentDir = dirname(__FILE__);
require_once("../classes/profile.php");
require_once '/etc/apache2/capstone-mysql/encrypted-config.php';

$profileId = $_SESSION['profileId'];
?>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<?php



try {
	//
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

//$profileId = $_SESSION['profileId'];

	$profileId = 1;

//	$userId = $_SESSION['user']['id'];

	$userId = 1;

	// Set your secret key: remember to change this to your live secret key in production
// See your keys here https://dashboard.stripe.com/account
	\Stripe\Stripe::setApiKey("sk_test_BQokikJOvBiI2HlWgH4olfQ2");

// Get the bank account details submitted by the form
	$token_id = $_POST['stripeToken'];

// Create a Recipient
	$recipient = \Stripe\Recipient::create(array(
			"first name" => $profileFirstname,
			"last name" => $profileLastname,
			"type" => "individual",
			"bank_account" => $token_id,
			"email" => "payee@example.com")
	);



	$profile = Profile::getProfileByProfileId($mysqli, $profileId);
	$profileType = $profile->getProfileType();
	$profileFirstname = $profile->getFirstName();
	$profileLastname = $profile->getLastName();
	$profilePhone = $profile->getPhone();
	$profileImage = $profile->getImagePath();
	$profileToken = $profile->getCustomerToken();

	// if user makes edits, update in profile
	if($_POST['stripeToken'] !== '') {
		$profileToken = $token_id;
		$profile->setCustomerToken($profileToken);
	}

	$profile->update($mysqli);


	echo "<p class=\"alert alert-success\">Profile (id = " . $profile->getProfileId() . ") updated!</p>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>

