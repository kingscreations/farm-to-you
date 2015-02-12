<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 2/12/2015
 * Time: 9:03 AM
 */
$currentDir = dirname(__FILE__);
require_once '../root-path.php';
require_once("../php/lib/header.php");
?>

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/2.7.5/idangerous.swiper.min.css"/>
<link rel="stylesheet" href="../css/main.css"/>

<div class="container">
	<div class=""col-sm-12">
	<h3>Welcome back!</h3>

	<form class="form-inline" id="signIn" method="post" action="../php/forms/sign-in-controller.php">

		<div class="form-group">
			<label for="inputEmail">Email:</label>
			<input type="text" class="form-control" id="inputEmail" name="inputEmail" placeholder="Please enter your Email">
		</div>

		<br>

		<div class="form-group">
			<label for="password">Your password:</label>
			<input type="password" id="password" class="password" autocomplete="off" onkeypress="displayCapsWarning(event,'ap_caps_warning', this);" value="" tabindex="6" size="20" maxlength="1024" name="password">
		</div>

		<br><br>

		<div>
			<input id="continue-input" type="submit" name="continue" tabindex="10" value="Start Shopping">
			<div class="ap_csm_marker" style="display:none;">
			</div>
		</div>
	</form>
	<p id="outputArea"></p>
</div>

</div>
