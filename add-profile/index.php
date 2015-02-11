<?php
$currentDir = dirname(__FILE__);
require_once '../root-path.php';
require_once("../php/lib/header.php");
?>

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/2.7.5/idangerous.swiper.min.css"/>
<link rel="stylesheet" href="../css/main.css"/>

<div class="container">
	<h2>Create Profile</h2>

	<form id="addprofile" class="form-inline" method="post" action="../php/forms/add-profile-controller.php">

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
			<input type="file" class="form-control" id="InputImage" name="InputImage" value="">
		</div>

		<br>

		<div class="form-group">
			<input type="submit" class="form-control" id="inputSubmit" name="inputSubmit">
		</div>

	</form>
</div>