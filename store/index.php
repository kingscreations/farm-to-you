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
require_once("../php/classes/orderproduct.php");

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

// get the product id from the current url
if(!@isset($_GET['store']) && !@isset($_GET['product'])) {
header('Location: ../php/lib/404.php');
}

$productId = filter_var($_GET['product'], FILTER_SANITIZE_NUMBER_INT);
$product  = Product::getProductByProductId($mysqli, $productId);

if(@isset($_GET['store'])) {
$storeId = filter_var($_GET['store'], FILTER_SANITIZE_NUMBER_INT);
} else {
$storeId = $product->getStoreId();
}

$store    = Store::getStoreByStoreId($mysqli, $storeId);

// get all the products of the current product store
$storeProducts = Product::getAllProductsByStoreId($mysqli, $store->getStoreId());

if(!in_array($product, $storeProducts)) {
header('Location: ../php/lib/404.php');
}

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