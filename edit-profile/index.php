<?php
$currentDir = dirname(__FILE__);
require_once ("../root-path.php");
require_once("../php/lib/header.php");
require_once("../dummy-session-single.php");
?>


	<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script src="../js/edit-profile.js"></script>

<!--Form to edit a profile-->
<div class="container">
	<h2>Edit Profile</h2>

	<form id="editProfile" class="form-inline" method="post" action="../php/forms/edit-profile-controller.php" enctype="multipart/form-data">

		<div class="form-group">
			<label for="inputFirstname">First Name:</label>
			<input type="text" class="form-control" id="inputFirstname" name="inputFirstname" placeholder="Enter First Name">
		</div>

		<br>

		<div class="form-group">
			<label for="inputLastname">Last Name:</label>
			<input type="text" class="form-control" id="inputLastname" name="inputLastname" placeholder="Enter Last Name">
		</div>

		<br>

		<div class="form-group">
			<label for="inputType">Profile Type:</label>
			<input type="radio" class="form-control" name="inputType" id="inputType" value="m">Merchant
			<input type="radio" class="form-control" name="inputType" id="inputType" value="c">Client
		</div>

		<br>

		<div class="form-group">
			<label for="inputPhone">Phone Number:</label>
			<input type="tel" class="form-control" id="inputPhone" name="inputPhone" placeholder="Enter Phone Number">
		</div>

		<br>

		<div class="form-group">
			<label for="inputImage">Profile Image</label>
			<input type="file" class="form-control" id="inputImage" name="inputImage" value="">
		</div>

		<br>

		<div class="form-group">
			<input type="submit" class="form-control" id="inputSubmit" name="inputSubmit">
		</div>

	</form>
	<p id="outputArea" style=""></p>
</div>

<?php
require_once("../php/lib/footer.php")
?>