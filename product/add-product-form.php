<?php
$currentDir = dirname(__FILE__);
require_once '../root-path.php';
require_once("../php/lib/header.php");
?>


<div class="container">
	<h2>Add Product</h2>

	<form class="form-inline" method="post" action="add-product-controller.php">

		<div class="form-group">
			<label for="InputProductName">Product Name:</label>
			<input type="text" class="form-control" name="InputProductName" id="InputProductName" placeholder="Product Name">
		</div>

		<br>

		<div class="form-group">
			<label for="InputProductPrice">Product Price:</label>
			<input type="text" class="form-control" name="InputProductPrice" id="InputProductPrice" placeholder="Price">
		</div>

		<br>

		<div class="form-group">
			<label for="InputProductType">Product Type:</label>
			<input type="text" class="form-control" name="InputProductType" id="InputProductType" placeholder="Product Type">
		</div>

		<br>

		<div class="form-group">
			<label for="InputProductWeight">Product Weight:</label>
			<input type="text" class="form-control" name="InputProductWeight" id="InputProductWeight" placeholder="Weight">
		</div>

		<br>

		<div class="form-group">
			<label for="InputProductImage">Product Image:</label>
			<input type="file" class="form-control" name="InputProductImage" id="InputProductImage" value="">
		</div>

	</form>