<?php
$currentDir = dirname(__FILE__);
require_once ("../root-path.php");
require_once("../php/lib/header.php");
require_once("../php/classes/product.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

?>


	<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script src="../js/add-product.js"></script>

	<div id="multi-menu" class="col-md-3">
		<ul class="nav nav-pills nav-stacked">
			<li><a href="../edit-profile/index.php">Edit Profile</a></li>
			<li><a href="../add-store/index.php">Manage Stores</a></li>
			<li><a href="#">List of Orders</a></li>
			<li><a href="#">Account Settings</a></li>
		</ul>
	</div>

<!--Form for adding a new product-->
<div class="col-md-9">
	<h2>Add Product</h2>

	<form id="addProduct" class="form-inline" method="post" action="../php/forms/add-product-controller.php" novalidate>

		<div class="form-group">
			<label for="inputProductName">Product Name:</label>
			<input type="text" class="form-control" name="inputProductName" id="inputProductName" placeholder="Product Name">
		</div>

		<br>

		<div class="form-group">
			<label for="inputProductPrice">Product Price:</label>
			<input type="text" class="form-control" name="inputProductPrice" id="inputProductPrice" placeholder="Price">
		</div>

		<br>

		<div class="form-group">
			<label for="inputProductDescription">Product Description:</label>
			<textarea class="form-control" name="inputProductDescription" id="inputProductDescription" placeholder="Write Product Description here."></textarea>
		</div>

		<br>

		<div class="form-group">
			<label for="inputProductPriceType">Product Price Type:</label>
			<input type="radio" class="form-control" name="inputProductPriceType" id="inputProductPriceType" value="w">By Weight
			<input type="radio" class="form-control" name="inputProductPriceType" id="inputProductPriceType" value="u">Per Unit
		</div>

		<br>

		<div class="form-group">
			<label for="inputProductWeight">Product Weight:</label>
			<input type="text" class="form-control" name="inputProductWeight" id="inputProductWeight" placeholder="Weight">
		</div>

		<br>

		<div class="form-group">
			<label for="inputStockLimit">Current Stock Amount:</label>
			<input type="number" class="form-control" name="inputStockLimit" id="inputStockLimit" step="1">
		</div>

		<br>

		<div class="form-group">
			<label for="inputProductImage">Product Image:</label>
			<input type="file" class="form-control" name="inputProductImage" id="inputProductImage" value="">
		</div>

		<br>

		<div class="form-group">
			<input type="submit" class="form-control" id="inputSubmit" name="inputSubmit" value="Submit">
		</div>

		<p id="outputArea" style=""></p>

	</form>
</div>

<div class="container">
	<?php

	try {
	// get the credentials information from the server and connect to the database
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	// grab all stores by profile id in dummy session
	$products = Product::getAllProductsByStoreId($mysqli, $_SESSION["storeId"]);

	// create table of existing stores
	if($products !== null) {

	echo '<table class="table table-responsive">';
		echo '<tr>';
			echo '<th>Product</th>';
			echo '<th></th>';
			echo '</tr>';
		foreach($products as $product) {
		$productId = $product->getProductId();
		$productName = $product->getProductName();
		echo '<tr>';
			echo '<td>'. $productName . '</td>';

			echo '<td><button id="'.$productId.'" class="btn btn-default editButton">Edit</button></td>';
			echo '<td><button id="'.$productId.'" class="btn btn-default deleteProductButton">Delete</button></td>';
			echo '</tr>';
		}
		echo '</table>';
	}

	} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
	}

	?>

	<div class="form-group">
		<button class="btn btn-default addButton" id="back">Back</button>
	</div>

</div>

	<?php
require_once("../php/lib/footer.php")
?>