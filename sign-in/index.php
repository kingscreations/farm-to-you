<?php
/**
 * Sign in index
 * User: jason
 * Date: 2/12/2015
 * Time: 9:03 AM
 */

$currentDir = dirname(__FILE__);
require_once '../root-path.php';
require_once("../php/lib/signing-in-header.php");
?>

<div class="row-fluid">
	<div class=""col-sm-12">
	<h3>Welcome Back!</h3>

	<br>

	<form class= "form" method="post" name="signIn" id="signIn" action="../php/forms/sign-in-controller.php">
		<fieldset>
			<?php
			echo generateInputTags();
			?>
			<label>Your email:</label>
			<input type="text" name="email" id="email" value="" size="45" />
			<br><br>
			<label>Enter your password:</label>
			<input type="password" name="password" id="password" value="" size="38" />
			<br><br>
			<input type="submit" value="Log In" id="submit">
		</fieldset>
	</form>

	<p id="outputArea"></p>
</div>

</div><!-- end row-fluid -->
<?php
require_once ("../php/lib/footer.php");
?>
