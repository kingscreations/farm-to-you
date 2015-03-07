<?php
$currentDir = dirname(__FILE__);
require_once ("../root-path.php");
require_once("../php/lib/header.php");

require_once("../php/classes/profile.php");
require_once '/etc/apache2/capstone-mysql/encrypted-config.php';

?>

<?php

mysqli_report(MYSQLI_REPORT_STRICT);

// get the credentials information from the server
$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
$configArray = readConfig($configFile);

// connection
$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"], $configArray["database"]);

//$userId = 1;
if(@isset($_SESSION['user']['id'])) {
	$userId = $_SESSION['user']['id'];
} else {
	header('Location: ../sign-up');
}

$hasProfile = Profile::getProfileByUserId($mysqli, $userId);

if($hasProfile === null) {

	echo '<div class="container-fluid transparent-form ">
	<h2>Create Profile</h2>

	<form id="addProfile" class="form-inline" method="post" action="../php/forms/add-profile-controller.php" enctype="multipart/form-data">';
		echo generateInputTags();
		echo '<div class="form-group">
			<label for="inputFirstname">First Name:</label>
			<input class="form-control" type="text" maxlength="45" size="45" aria-required="true" aria-invalid ="false" id="inputFirstname" name="inputFirstname" placeholder="Enter First Name">
		</div>

		<br>

		<div class="form-group">
			<label for="inputLastname">Last Name:</label>
			<input type="text"  maxlength="45" size="45" class="form-control" id="inputLastname" name="inputLastname" placeholder="Enter Last Name">
		</div>

		<br>

		<div class="form-group">
			<label for="inputType">Profile Type:</label>
			<input type="radio" class="form-control" name="inputType" id="inputType" value="m">Merchant
			<input type="radio" class="form-control" name="inputType" id="inputType" value="c">Customer
		</div>

		<br>

		<div class="form-group">
			<label for="inputPhone">Phone Number:</label>
			<input type="tel" size="39" class="form-control" id="inputPhone" name="inputPhone" placeholder="Enter Phone Number">
		</div>

		<br>

		<div class="form-group">
			<label for="inputImage">Profile Image</label>
			<input type="file" class="form-control" id="inputImage" name="inputImage">
		</div>

		<br>

		<div class="form-group">
			<input type="submit" class="form-control" id="inputSubmit" name="inputSubmit" value="Submit">
		</div>

	</form>

	<p id="outputArea" style=""> </p>
</div>';
} else {


	echo '<div class="container transparent-form">
				<h3>You already have a Profile, </br>if you would like to edit your profile </br>please click here: <form action="../edit-profile/index.php"><input class ="submit" id="inputSubmit" type="submit" value="Edit Profile">';
				echo generateInputTags();
				echo '</form>
						</h3>
			</div>';
};

require_once("../php/lib/footer.php");

?>

<script src="../js/add-profile.js"></script>