<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 2/11/2015
 * Time: 3:46 PM
 */

$currentDir = dirname(__FILE__);
require_once '../root-path.php';
require_once("../php/lib/header.php");
?>

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/2.7.5/idangerous.swiper.min.css"/>
<link rel="stylesheet" href="../css/main.css"/>

<div class="container">
	<h3>Sign Up!</h3>

	<form id="signUp" class="form-inline" method="post" action="../php/forms/sign-up-controller.php">

		<div class="form-group">
			<label for="inputEmail">Email:</label>
			<input type="text" class="form-control" id="inputEmail" name="inputEmail" placeholder="Enter Email">
		</div>

		<br>

		<div class="form-group">
			<label for="password">Enter a password:</label>
				<input id="password" type="password" class="password" autocomplete="off" onkeypress="displayCapsWarning(event,'ap_caps_warning', this);" value="" tabindex="6" size="20" maxlength="1024" name="password">
		</div>

		<br>

		<div>
			<div class="form-group">
				<label for="password_check">Please re-enter your password: </label>
				<input id="password_check" type="password" class="password" autocomplete="off" onkeypress="displayCapsWarning(event,'ap_caps_warning', this);" value="" tabindex="7" size="20" maxlength="1024" name="passwordCheck">
			</div>
		</div>

		<br>

		<div>
			<input id="continue-input" type="submit" name="continue" tabindex="10" value="Get Started">
			<div class="ap_csm_marker" style="display:none;">
			</div>
			<br><br>
			Please open the email we just sent to you and click the link within to finish your registration.<br>
			Thank you for signing up with Farm-to-you! We will see you at the check-out!

		</div>

		<br>

		<div>

</div>