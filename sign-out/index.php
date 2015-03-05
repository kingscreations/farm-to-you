<?php
/**
 * Sign Out Index
 * User: jason
 * Date: 2/12/2015
 * Time: 9:03 AM
 */

$currentDir = dirname(__FILE__);
require_once '../root-path.php';
require_once("../php/lib/header.php");

// determine if session has variables we dont want to loose, clear session if not
if(@isset($_SESSION) && @isset($_SESSION['products'])) {
	// clear the session variables and destroy
	unset($_SESSION['user']);
	unset($_SESSION['profile']);

} else {
	session_destroy();
}

?>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<h3>You are now signed out. Thank you for visiting Farm to You. We hope to see you again soon.</h3>
			<span class="hidden root-url"><?php echo SITE_ROOT_URL; ?></span>
		</div>
	</div><!-- end row -->
</div><!-- end container-fluid -->

<script src="../js/sign-out.js"></script>

<?php require_once ("../php/lib/footer.php"); ?>