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

<div class="sign-up">
	<div class="sign-up-form">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12">
					<h3>Sign Up!</h3>
					<br>
					<form class= "form" method="post" id="signUp" action="../php/forms/sign-up-controller.php">
						<fieldset>
							<?php
							//					echo generateInputTags();
							?>
							<label>Your email!:</label>
							<input type="text" name="inputEmail" id="inputEmail" value="" size="45" />
							<br><br>
							<label>Enter a password:</label>
							<input type="password" name="password1" id="password1" value="" size="38" />
							<br><br>
							<label>Please re-Enter Password:</label>
							<input type="password" name="passwordCheck" id="passwordCheck" value="" size="29" />
							<br><br>
							<input type="submit" value="Let's get started!" id="submit">
						</fieldset><!-- fieldset -->
					</form><!-- form -->

					<p id="outputArea"></p>
				</div> <!-- col xs12 -->
			</div> <!-- end row -->
		</div><!-- end container fluid -->
	</div><!--.sign-up-form-->


</div><!--.sign-up -->



<?php require_once ("../php/lib/footer.php"); ?>
