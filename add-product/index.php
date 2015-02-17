<?php
$currentDir = dirname(__FILE__);
require_once '../root-path.php';
require_once("../php/lib/header.php");
?>

<!--Form for adding a new product-->
<div class="container">
	<h2>Add Product</h2>

	<form class="form-inline" method="post" action="../php/forms/add-product-controller.php">

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
			<label for="inputProductType">Product Type:</label>
			<input type="text" class="form-control" name="inputProductType" id="inputProductType" placeholder="Product Type">
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
			<input type="submit" class="form-control" id="inputSubmit" name="inputSubmit">
		</div>

	</form>

	<?php
require_once("../php/lib/footer.php")
?>