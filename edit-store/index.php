<?php
/**
 * @author Alonso Indacochea <alonso@hermesdevelopment.com>
 */

// header
$currentDir = dirname(__FILE__);
require_once("../root-path.php");
require_once("../php/lib/header.php");

// classes
require_once("../php/classes/store.php");

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

try {

	// get the credentials information from the server and connect to the database
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	// grab store with id from session
	$store = Store::getStoreByStoreId($mysqli, $_SESSION['storeId']);

	// create variables for attribute values
	$storeName = $store->getStoreName();
	$storeDescription = $store->getStoreDescription();

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>

<!--js validation + ajax call-->
<script src="../js/edit-store.js"></script>

<div class="row-fluid">
	<div class="col-sm-12">
		<h2>Edit Store</h2>
		<form class="form-inline" id="editStoreController" method="post" action="../php/forms/edit-store-controller.php" enctype="multipart/form-data">
			<div class="form-group">
				<label for="editStoreName">Store Name</label>
				<input type="text" class="form-control" name="editStoreName" id="editStoreName" value="<?php echo $storeName;?>">
			</div>
			<br>
			<div class="form-group">
				<label for="editStoreDescription">Store Description</label>
				<input type="text" class="form-control" name="editStoreDescription" id="editStoreDescription" value="<?php echo $storeDescription;?>">
			</div>
			<br>
			<div class="form-group">
				<label for="editInputImage">Image</label>
				<input type="file" class="form-control" name="editInputImage" id="editInputImage">
			</div>
			<br>
			<br>
			<br>
			<div class="form-group">
				<input type="submit" class="form-control" id="editSubmit" name="editSubmit" value="Submit">
			</div>
			<br>
			<br>
			<p id="outputArea"></p>
		</form>
		<br>
	</div>
</div>

<!--footer-->
<?php require_once "../php/lib/footer.php";?>