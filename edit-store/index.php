<?php
//session_start();
$currentDir = dirname(__FILE__);

//require_once("../dummy-session-single.php");
require_once("../root-path.php");
require_once("../php/lib/header.php");
require_once("../php/classes/store.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");



try {
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$store = Store::getStoreByStoreId($mysqli, 1);
//echo 'view';
//	var_dump($store);
	$storeName = $store->getStoreName();
	$storeDescription = $store->getStoreDescription();
//	var_dump($store);


//	$_SESSION['store'] = array(
//		'id' 				=> $store->getStoreId(),
//		'name'			=> $store->getStoreName(),
//		'description'	=> $store->getStoreDescription(),
//		'image'			=> $store->getImagePath(),
//		'creation'		=> $store->getCreationDate()
//	);

//	var_dump($_SESSION['store']['name']);
//	var_dump($_SESSION['store']['description']);
//	var_dump($_SESSION['store']['image']);

//	$storeName = $_SESSION['store']['name'];


} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}


?>

	<script src="../js/edit-store.js"></script>

	<div class="row-fluid">
	<div class="col-sm-12">
		<h2>Edit Store</h2>

	<form class="form-inline" id="editStoreController" method="post" action="../php/forms/edit-store-controller.php" enctype="multipart/form-data">

		<div class="form-group">
			<label for="editStoreName">Store Name</label>
			<input type="text" class="form-control" name="editStoreName" id="editStoreName" value=<?php echo $storeName;?>>
		</div>

		<br>

		<div class="form-group">
			<label for="editStoreDescription">Store Description</label>
			<input type="text" class="form-control" name="editStoreDescription" id="editStoreDescription" value=<?php echo $storeDescription;?>>
		</div>

		<br>

		<div class="form-group">
			<label for="editInputImage">Image</label>
			<input type="file" class="form-control" name="editInputImage" id="editInputImage">
		</div>

		<br>
		<br>
		<br>
		<button type="submit">Submit</button>
		<br>
		<br>
		<p id="outputArea"></p>
	</form>
	<br>
</div>
</div>
<?php


require_once "../php/lib/footer.php";

?>