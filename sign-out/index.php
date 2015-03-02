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
	session_unset();
	session_destroy();
	$_SESSION = array();
	session_start();
}





?>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<h3>You are now signed out. Thank you for visiting farm-to-you. We hope to see you again soon.</h3>



		</div>
	</div><!-- end row -->
</div><!-- end container-fluid -->

<?php require_once ("../php/lib/footer.php"); ?>