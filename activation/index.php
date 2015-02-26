<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 2/21/2015
 * Time: 12:22 PM
 */
$currentDir = dirname(__FILE__);
require_once '../root-path.php';
require_once("../php/lib/header.php");
require_once("../php/classes/user.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

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
$_SESSION['user'] = array(
	'id' => $mysqlUser->getUserId()
);

$currentPathExploded = explode("/", $_SERVER["PHP_SELF"]);
if(empty($currentPathExploded)) {
	throw new RangeException('Impossible to explode the path');
}	$url = "https://". $_SERVER["SERVER_NAME"] . '/' . $currentPathExploded[1] . '/' .
		$currentPathExploded[2];
?>

<div class="row-fluid">
	<div class=""col-sm-12">
		<h3>Your registration is complete. </br> Thank you for signing up with farm to you!</h3>


<p>
	<ul>
		<li>
			<a href="<?php echo $url ?>">Continue to home page</a>
		</li>
	</ul>
</p>

	</div>

</div><!-- end row-fluid -->
<?php
require_once ("../php/lib/footer.php");
?>