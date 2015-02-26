<?php
$currentDir = dirname(__FILE__);
require_once ("../root-path.php");
require_once("../php/lib/header.php");
require_once("../php/classes/profile.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

//require_once("../dummy-session-single.php");

mysqli_report(MYSQLI_REPORT_STRICT);
$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);


$profile = Profile::getProfileByProfileId($mysqli, $_SESSION["profileId"]);

$profileFirstname = $profile->getFirstName();
$profileLastname = $profile->getLastName();
$profilePhone = $profile->getPhone();
$profileImage = $profile->getImagePath();


?>


	<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script src="../js/edit-profile.js"></script>


	<div id="multi-menu" class="col-md-3">
		<ul class="nav nav-pills nav-stacked">
			<li class="active"><a href="../edit-profile/index.php">Edit Profile</a></li>
			<li><a href="../add-store/index.php">Manage Stores</a></li>
			<li><a href="#">List of Orders</a></li>
			<li><a href="#">Account Settings</a></li>
		</ul>
	</div>
<!--Form to edit a profile-->
<div class="col-md-9">

	<h2>Edit Profile</h2>

	<form id="editProfile" class="form-inline" method="post" action="../php/forms/edit-profile-controller.php" enctype="multipart/form-data">

		<div class="form-group">
			<label for="inputFirstname">First Name:</label>
			<input type="text" class="form-control" id="inputFirstname" name="inputFirstname" placeholder="<?php echo $profileFirstname ?>" value="<?php echo $profileFirstname ?>">
		</div>

		<br>

		<div class="form-group">
			<label for="inputLastname">Last Name:</label>
			<input type="text" class="form-control" id="inputLastname" name="inputLastname" placeholder="<?php echo $profileLastname ?>" value="<?php echo $profileLastname ?>">
		</div>

		<br>

		<div class="form-group">
			<label for="inputPhone">Phone Number:</label>
			<input type="tel" class="form-control" id="inputPhone" name="inputPhone" placeholder="<?php echo $profilePhone ?>" value="<?php echo $profilePhone ?>">
		</div>

		<br>

		<div class="form-group">
			<label for="inputImage">Profile Image</label>
			<input type="file" class="form-control" id="inputImage" name="inputImage" value="">
		</div>

		<br>

		<div class="form-group">
			<input type="submit" class="form-control" id="inputSubmit" name="inputSubmit" value="Submit">
		</div>

	</form>
	<p id="outputArea" style=""></p>
</div>

<?php
require_once("../php/lib/footer.php")
?>