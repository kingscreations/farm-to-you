<?php

/**
* @author Florian Goussin <florian.goussin@gmail.com>
*/

// header
$currentDir = dirname(__FILE__);
require_once('../root-path.php');
require_once('../php/lib/header.php');

// credentials
require_once('/etc/apache2/capstone-mysql/encrypted-config.php');

// paths file
require_once('../paths.php');

// model
require_once("../php/classes/product.php");
require_once("../php/classes/user.php");
require_once("../php/classes/profile.php");
require_once("../php/classes/store.php");
require_once("../php/classes/location.php");
require_once("../php/classes/storelocation.php");

// path for the config file
$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";

mysqli_report(MYSQLI_REPORT_STRICT);

try {

	// get the credentials information from the server and connect to the database
	$configArray = readConfig($configFile);

	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	$user     = User::getUserByUserId($mysqli, 1);
	$profile  = Profile::getProfileByProfileId($mysqli, 1);
	$store    = Store::getStoreByStoreId($mysqli, 1);

	// TODO link this page with the index page AND search page
	$product  = Product::getProductByProductId($mysqli, 1);

	// get all the locations from the current store
	$storeLocations = StoreLocation::getAllStoreLocationsByStoreId($mysqli, $store->getStoreId());

	$locations = [];
	if($storeLocations !== null) {
		foreach($storeLocations as $storeLocation) {
			$location = Location::getLocationByLocationId($mysqli, $storeLocation->getLocationId());
			$locations[] = $location;
		}
	}

} catch(Exception $exception) {
	echo 'Exception: '. $exception->getMessage() .'<br/>';
	echo $exception->getFile(). ':' .$exception->getLine();
}

?>

<div class="row">
	<div class="col-sm-7">
		<?php

		$baseUrl             = CONTENT_ROOT_URL . 'images/product/';
		$basePath            = CONTENT_ROOT_PATH . 'images/product/';
		$imagePlaceholderSrc = 'product-placeholder.jpg';
		$imageSrc            = 'product-'. $product->getProductId() .'.jpg';

		// show a placeholder if the product is not associated with an image
		if(file_exists($basePath . $imageSrc)) {
		?>
			<img src="<?php echo $baseUrl . $imageSrc; ?>" alt="<?php echo $product->getProductName(); ?>"/>
		<?php } else { ?>
			<img src="<?php echo $baseUrl . $imagePlaceholderSrc; ?>" alt="<?php echo $product->getProductName(); ?>"/>
		<?php } ?>
	</div>
	<div class="col-sm-5"></div>
</div>