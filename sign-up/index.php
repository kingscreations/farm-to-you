<?php
/**
 * Sign-up index
 * User: jason
 * Date: 2/11/2015
 * Time: 3:46 PM
 */

$currentDir = dirname(__FILE__);
require_once ('../root-path.php');
require_once("../php/lib/header.php");

?>

	<div class="container-fluid sign-up">
		<div class="sign-up-form">
			<div class="row">
				<div class="col-xs-12">
					<h3>Sign Up!</h3>
					<br>
					<form class= "form" method="post" id="signUp" action="../php/forms/sign-up-controller.php">
						<?php echo generateInputTags(); ?>
						<fieldset>
							<label>Your email:</label>
							<input class="form-control-custom" type="text" name="inputEmail" id="inputEmail" value="" size="relative" />
							<br><br>
							<label>Enter a password:</label>
							<input class="form-control-custom" type="password" name="password1" id="password1" value="" size="relative" />
							<br><br>
							<label>Please re-enter your password:</label>
							<input class="form-control-custom" type="password" name="passwordCheck" id="passwordCheck" value="" size="relative" />
							<br><br>
							<input type="submit" value="Let's get started!" id="submit" class="btn btn-default mt30">
						</fieldset><!-- fieldset -->
					</form><!-- form -->

					<p id="outputArea"></p>
				</div> <!-- col xs12 -->
			</div> <!-- end row -->
		</div><!--.sign-up-form-->
	</div><!-- end container fluid -->

<script src="../js/sign-up.js"></script>

<?php require_once ("../php/lib/footer.php"); ?>
