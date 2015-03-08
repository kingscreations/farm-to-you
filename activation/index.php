<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 2/21/2015
 * Time: 12:22 PM
 */
$currentDir = dirname(__FILE__);
require_once '../root-path.php';

session_start();

if(!@isset($_GET['activation'])) {
	header('Location: ../sign-up/index.php');
}

session_abort();

require_once("../php/lib/header.php");
require_once("../php/classes/user.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

require_once('../paths.php');

try {
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

} catch(Exception $exception) {
	echo "Exception: " . $exception->getMessage() . "<br/>";
	echo $exception->getFile() . ":". $exception->getLine();
	}
// get activation code from email and sanitize
$activation = $_GET['activation'];
$activation = filter_var($activation, FILTER_SANITIZE_STRING);
$mysqlUser = User::getUserByActivation($mysqli, $activation);


// create session id specific to this user
$_SESSION['userId'] = $mysqlUser->getUserId();

?>
	<div class="activation">
		<div class="activation-form">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12">
						<h3>Your registration is complete. <br> Thank you for joining Farm to You!</h3>
						<p>
							<ul>
								<li>
									<a href="../add-profile/index.php">Continue to create your profile!</a>
								</li>
							</ul>

					</div><!-- end col-sm-12 -->
				</div><!-- end row -->
			</div><!-- end container-fluid -->
		</div><!-- end activation-form -->
	</div><!-- end activation -->

<?php require_once ("../php/lib/footer.php"); ?>