<?php
$currentDir = dirname(__FILE__);
require_once ("../root-path.php");
require_once("../php/lib/header.php");
require_once("../php/classes/profile.php");
require_once '/etc/apache2/capstone-mysql/encrypted-config.php';

$profileId = $_SESSION['profileId'];

mysqli_report(MYSQLI_REPORT_STRICT);
$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

//$profileFromUser = Profile::getProfileByUserId($mysqli,$_SESSION['user']['id']);
//$profileId = $profileFromUser->getProfileId();

$profile = Profile::getProfileByProfileId($mysqli, $profileId);

$profileFirstname = $profile->getFirstName();
$profileLastname = $profile->getLastName();
$profilePhone = $profile->getPhone();
$profileImage = $profile->getImagePath();
$profileType = $profile->getProfileType();


if($profileType !== "m") {
	echo 'YOU HAVE NO POWER HERE!';
	exit();
}

?>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>


<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<h2>Add Bank Account</h2>
			<form id="bank-info" class="form-inline" method="post" action="../php/forms/bank-account-controller.php" enctype="multipart/form-data">
				<div class="form-group">
					<label for="inputFirstname">First Name: <?php echo $profileFirstname ?></label>
				</div>

				<br>

				<div class="form-group">
					<label for="inputLastname">Last Name: <?php echo $profileLastname ?></label>
				</div>

				<br>

				<div class="form-group">
					<label for="bank-account">Bank Account Number:</label>
					<input type="text" class="form-control" id="stripeToken" name="stripeToken">
				</div>

				<br>

				<div class="form-group">
					<input type="submit" class="form-control">
				</div>

				</form>
		</div>
	</div>
</div>
