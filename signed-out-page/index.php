<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 2/20/2015
 * Time: 1:12 PM
 */
session_start();
$currentDir = dirname(__FILE__);
require_once '../root-path.php';
require_once("../php/lib/signed-out-header.php");
?>
// clear the session variables
$_SESSION = array();

// destroy the session
session_destroy();

<div class="row-fluid">
	<div class=""col-sm-12">
		<h3>You are now signed out. Thank you for visiting farm-to-you. We hope to see you again soon.</h3>



	</div>

</div><!-- end row-fluid -->
<?php
require_once ("../php/lib/footer.php");
?>