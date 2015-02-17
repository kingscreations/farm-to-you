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

			<br>

		<form method="post" name="signUp" id="signUp" action="">
			<fieldset>
				<label>Your email:</label>
				<input class="form-control" type="text" name="inputEmail" id="inputEmail" value="" size="45" />
				<br><br>
				<label>Enter a password:</label>
				<input class="form-control" type="password" name="password" id="password" value="" size="38" />
				<br><br>
				<label>Please re-Enter Password:</label>
				<input class="form-control" type="password" name="passwordCheck" id="passwordCheck" value="" size="29" />
				<br><br>
				<input class="form-control" type="submit" value="Let's get started!" id="submit">
			</fieldset>
		</form>
	<p id="outputArea"></p>
	</div>
</div>

