<?php
$currentDir = dirname(__FILE__);
require_once '../root-path.php';
require_once("../php/lib/header.php");
?>

<div class="container">
	<h2>Edit Profile</h2>

	<form class="form-inline" method="post" action="edit-profile-controller.php">

		<div class="form-group">
			<label for="InputFirstname">First Name:</label>
			<input type="text" class="form-control" id="InputFirstname" name="InputFirstname" placeholder="Enter First Name">
		</div>

		<br>

		<div class="form-group">
			<label for="InputLastname">Last Name:</label>
			<input type="text" class="form-control" id="InputLastname" name="InputLastname" placeholder="Enter Last Name">
		</div>

		<br>

		<div class="form-group">
			<label for="InputType">Profile Type:</label>
			<input type="radio" class="form-control" name="InputType" id="InputType" value="m">Merchant
			<input type="radio" class="form-control" name="InputType" id="InputType" value="c">Client
		</div>

		<br>

		<div class="form-group">
			<label for="InputPhone">Phone Number:</label>
			<input type="tel" class="form-control" id="InputPhone" name="InputPhone" placeholder="Enter Phone Number">
		</div>

		<br>

		<div class="form-group">
			<label for="InputImage">Profile Image</label>
			<input type="file" class="form-control" id="InputImage" name="InputImage" value="">
		</div>

		<br>

		<div class="form-group">
			<input type="submit" class="form-control" id="InputSubmit" name="InputSubmit">
		</div>

	</form>
</div>