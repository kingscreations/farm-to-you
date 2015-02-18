<?php
session_start();
$currentDir = dirname(__FILE__);

require_once("../dummy-session-single.php");
require_once ("../root-path.php");
require_once("../php/lib/header.php");


$productName = $_SESSION['product']['name'];
$productPrice = $_SESSION['product']['price'];
$productDescription = $_SESSION['product']['description'];
$productPriceType = $_SESSION['product']['priceType'];
$productWeight = $_SESSION['product']['weight'];
$productStock = $_SESSION['product']['stock'];
$productImage = $_SESSION['product']['image']
?>
	<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script src="../js/edit-product.js"></script>



<!--Form to edit a product-->
<div class="container">
	<h2>Edit Product</h2>

	<form id="editProduct" class="form-inline" method="post" action="../php/forms/edit-product-controller.php" novalidate>

		<div class="form-group">
			<label for="editProductName">Product Name:</label>
			<input type="text" class="form-control" name="editProductName" id="editProductName" placeholder="<?php echo $_SESSION['product']['name'];?>" value="<?php echo $_SESSION['product']['name'];?>">
		</div>

		<br>

		<div class="form-group">
			<label for="editProductPrice">Product Price:</label>
			<input type="text" class="form-control" name="editProductPrice" id="editProductPrice" placeholder=<?php echo $_SESSION['product']['price'];?> value=<?php echo $_SESSION['product']['price'];?>>
		</div>

		<br>

		<div class="form-group">
			<label for="editProductDescription">Product Description:</label>
			<textarea class="form-control" name="editProductDescription" id="editProductDescription" placeholder=<?php echo $_SESSION['product']['description'];?> value=<?php echo $_SESSION['product']['description'];?>></textarea>
		</div>

		<br>

		<div class="form-group">
			<label for="editProductPriceType">Product Price Type:</label>
			<input type="radio" class="form-control" name="editProductPriceType" id="editProductPriceType" value="w">By Weight
			<input type="radio" class="form-control" name="editProductPriceType" id="editProductPriceType" value="u">Per Unit
		</div>

		<br>

		<div class="form-group">
			<label for="editProductWeight">Product Weight:</label>
			<input type="text" class="form-control" name="editProductWeight" id="editProductWeight" placeholder=<?php echo $_SESSION['product']['weight'];?> value=<?php echo $_SESSION['product']['weight'];?>>
		</div>

		<br>

		<div class="form-group">
			<label for="editStockLimit">Current Stock Amount:</label>
			<input type="number" class="form-control" name="editStockLimit" id="editStockLimit" step="1" placeholder=<?php echo $_SESSION['product']['stock'];?> value=<?php echo $_SESSION['product']['stock'];?>>
		</div>

		<br>

		<div class="form-group">
			<label for="editProductImage">Product Image:</label>
			<input type="file" class="form-control" name="editProductImage" id="editProductImage" value=<?php echo $_SESSION['product']['image'];?>>
		</div>

		<br>

		<div class="form-group">
			<input type="submit" class="form-control" id="editSubmit" name="editSubmit">
		</div>

	</form>

	<p id="outputArea" style=""></p>

</div>

	<?php
require_once("../php/lib/footer.php")
?>