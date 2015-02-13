<?php
/**
 * Sign-up index
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
	<div class=""col-sm-12">
		<h3>Sign Up!</h3>

<!--		<form class="form-inline" id="signUp" method="post" action="../php/forms/sign-up-controller.php">-->
<!---->
<!--			<div class="form-group">-->
<!--				<label for="inputEmail">Email:</label>-->
<!--				<input type="text" id="inputEmail" name="inputEmail" placeholder="Please enter your Email">-->
<!--			</div>-->

			<br>
		<form method="post" name="signUp" id="signUp" action="">
			<fieldset>
				<label>Your email:</label>
				<input type="text" name="inputEmail" id="inputEmail" value="" size="45" />
				<br><br>
				<label>Enter a password:</label>
				<input type="password" name="password" id="password" value="" size="38" />
				<br><br>
				<label>Please re-Enter Password:</label>
				<input type="password" name="passwordCheck" id="passwordCheck" value="" size="29" />
				<br><br>
				<input type="submit" value="Let's get started!" id="submit">
			</fieldset>
		</form>
<!--			<div class="form-group">-->
<!--				<label for="password">Now type in a password:</label>-->
<!--					<input type="password" id="password" class="password" autocomplete="off" onkeypress="displayCapsWarning(event,'ap_caps_warning', this);" value="" tabindex="6" size="20" maxlength="1024" name="password">-->
<!--			</div>-->
<!---->
<!--			<br>-->
<!---->
<!--			<div>-->
<!--				<div class="form-group">-->
<!--					<label for="password_check">Please re-enter your password: </label>-->
<!--					<input type="password" id="passwordCheck" class="passwordCheck" autocomplete="off" onkeypress="displayCapsWarning(event,'ap_caps_warning', this);" value="" tabindex="7" size="20" maxlength="1024" name="passwordCheck">-->
<!--				</div>-->
<!--			</div>-->
<!--			<br><br>-->
<!--			<div class="submit">-->
<!--				<button type="submit" id="submit">Let's get started!</button>-->
<!--			</div>-->


<!--				<br><br>-->
<!--				Please open the email we just sent to you and click the link within to finish your registration.<br>-->
<!--				Thank you for signing up with Farm-to-you. We will see you at the check-out!-->


			</div>
		</form>
		<p id="outputArea"></p>
	</div>

</div>
