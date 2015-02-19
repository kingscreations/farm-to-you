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

<div class="row-fluid">
	<div class=""col-sm-12">
		<h3>Sign Up!</h3>

		<br>

		<form class= "form" method="post" name="signUp" id="signUp" action="../php/forms/sign-up-controller.php">
			<fieldset>
				<?php
					echo generateInputTags();
				?>
				<label>Your email:</label>
				<input type="text" name="inputEmail" id="inputEmail" value="jason@jason.com" size="45" />
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

		<p id="outputArea"></p>
	</div>

</div><!-- end row-fluid -->
<?php
require_once ("../php/lib/footer.php");
?>
