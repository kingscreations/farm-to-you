<?php
session_start();

$imageBasePath = '/var/www/html/farmtoyou/images/store';
$imageFileName = 'store-$storeId.{jpg|png}';


function checkInputImage($inputImage) {
	// check extension for normal users
	$extensions = array("jpg", "jpeg", "png");
	$extension  = strtolower(end(explode(".", $inputImage["name"])));
	if(in_array($extension, $extensions) === false) {
		echo "this is not a valid file";
		return false;
	}

	// check file content for malicious users and totally incompetent users
	$mimeType = $inputImage["type"];
	if($mimeType !== 'image/png' || $mimeType !== 'image/jpeg') {
		echo 'Sorry, we only accept GIF and JPEG images\n';
		return false;
	}

	if($mimeType === "image/png") {
		if(($image = @imagecreatefrompng($inputImage["tmp_name"])) === false) {
			throw new InvalidArgumentException('The input png image format is incorrect');
		}
	}

	if($mimeType === "image/jpg") {
		if(($image = @imagecreatefromjpeg($inputImage["tmp_name"])) === false) {
			throw new InvalidArgumentException('The input jpg image format is incorrect');
		}
	}

	var_dump($image);

	// want to resize/crop/vandalize Alonso's images?
	// do so here!
//	imagedestroy($image);

	return true;
}

$currentDir = dirname(__FILE__);
require_once("../../dummy-session.php");
require_once ("../../root-path.php");

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/store.php");
require_once("../classes/location.php");
require_once("../classes/storelocation.php");
require_once("../classes/profile.php");
require_once("../classes/user.php");


// verify the form values have been submitted
//if(@isset($_POST["storeName"]) === false) {
//	echo "<p class=\"alert alert-danger\">Form values not complete. Verify the form and try again.</p>";
//}

try {

	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$profileId = $_SESSION['profile']['id'];

	if(!@isset($_POST["locationName"]) || !@isset($_POST["address1"]) ||
		!@isset($_POST["zipCode"]) || !@isset($_POST["city"]) || !@isset($_POST["state"]) || !@isset($_POST["storeName"])) {
		throw new Exception('missing a required field');
	}


	$inputImage = $_FILES['inputImage'];

	$location = new Location(null, $_POST["locationName"], $_POST["country"], $_POST["state"], $_POST["city"],
		$_POST["zipCode"], $_POST["address1"], $_POST["address2"]);
	$store = new Store(null, $profileId, $_POST["storeName"], $_FILES["imagePath"], null, $_POST["storeDescription"]);

	$store->insert($mysqli);
	$storeId = $store->getStoreId();
	$location->insert($mysqli);
	$locationId = $location->getLocationId();
	$storeLocation = new StoreLocation($storeId, $locationId);
	$storeLocation->insert($mysqli);

//	$storeNames = Store::getAllStoresByProfileId($mysqli, $store->getProfileId());

	$_SESSION['store'] = array(
		'id' 				=> $store->getStoreId(),
		'name'			=> $store->getStoreName(),
		'description'	=> $store->getStoreDescription(),
		'image'			=> $store->getImagePath(),
		'creation'		=> $store->getCreationDate()
	);

	echo "<p class=\"alert alert-success\">" . $store->getStoreName() . " added!</p><br><p class=\"alert alert-success\">" . $location->getLocationName() . " added!</p>";

	?>
	<div class="row-fluid">
	<div class="col-sm-12">
	<h3><strong><?php echo $_SESSION['store']['name']; ?></strong>
	<br>

	<a href="../edit-store/index.php" class="btn btn-info" role="button">Edit Store</a>	<br>


<?php
	} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}
?>