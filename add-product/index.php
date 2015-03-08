<?php

$currentDir = dirname(__FILE__);
require_once ("../root-path.php");

session_start();

if(!@isset($_SESSION['storeId'])) {
	header('Location: ../sign-in/index.php');
}

session_abort();

require_once("../php/lib/header.php");

?>

<div class="container-fluid container-margin-sm transparent-form user-account" id="add-product">
	<div class="row">
		<div id="multi-menu" class="col-md-3 hidden-sm hidden-xs transparent-menu">
			<ul class="nav nav-pills nav-stacked">
				<li><a href="../edit-profile/index.php">Edit Profile</a></li>
				<li class="active"><a href="../add-store/index.php">Manage Stores</a></li>
				<li><a href="../merchant-order-list/index.php">List of Orders</a></li>
				<li><a href="../bank-account/index.php">Bank Account</a></li>
			</ul>
		</div>

		<div class="dropdown hidden-lg hidden-md" style="position:relative">
			<a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Menu<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="../edit-profile/index.php">Edit Profile</a></li>
				<li class="active"><a href="../add-store/index.php">Manage Stores</a></li>
				<li><a href="../merchant-order-list/index.php">List of Orders</a></li>
				<li><a href="../bank-account/index.php">Bank Account</a></li>
			</ul>
		</div>

		<!--Form for adding a new product-->
		<div class="col-md-9">

			<form id="addProduct" class="form-inline" method="post" action="../php/forms/add-product-controller.php" novalidate>
				<div class="center">
					<h2>Add Product</h2>
				</div>
				<?php echo generateInputTags(); ?>
				<div class="form-group">
					<label for="inputProductName">Product Name:</label>
					<input type="text" class="form-control" name="inputProductName" id="inputProductName" placeholder="Product Name">
				</div>

				<br>

				<div class="form-group">
					<label for="inputProductPrice">Product Price : $</label>
					<input type="text" class="form-control" name="inputProductPrice" id="inputProductPrice" placeholder="Price">
				</div>

				<br>

				<div class="form-group">
					<label for="inputProductDescription">Product Description:</label>
					<textarea class="form-control" name="inputProductDescription" id="inputProductDescription" placeholder="Write Product Description here."></textarea>
				</div>

				<br>

				<div class="form-group hidden-xs">
					<label for="inputProductPriceType">Sold By:</label>
					<input type="radio" class="form-control" name="inputProductPriceType" id="inputProductPriceType" value="w"> By Weight
					<input type="radio" class="form-control" name="inputProductPriceType" id="inputProductPriceType" value="u"> Per Unit
				</div>
				<div class="form-group visible-xs">
					<label for="inputProductPriceType">Sold By:</label>
					<input type="radio" name="inputProductPriceType" id="inputProductPriceType" value="w"> By Weight
					<input type="radio" class="ml1" name="inputProductPriceType" id="inputProductPriceType" value="u"> Per Unit
				</div>

				<br>

				<div class="form-group">
					<label for="inputProductWeight">Product Weight (lbs.):</label>
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

					<div class="form-group visible-xs">
						<label for="addTags">Tags:</label>
						<span><input type="text" class="input-tag form-control distinctTags iblock" id="addTags1" name="addTags1"></span>
						<span><input type="text" class="input-tag form-control distinctTags iblock" id="addTags2" name="addTags2"></span>
						<span><input type="text" class="input-tag form-control distinctTags iblock mt10" id="addTags3" name="addTags3"></span>
						<span><input type="text" class="input-tag form-control distinctTags iblock" id="addTags4" name="addTags4"></span>
					</div>
					<div class="form-group hidden-xs">
						<label for="addTags">Tags:</label>
						<span><input type="text" class="input-tag form-control distinctTags iblock" id="addTags1" name="addTags1"></span>
						<span><input type="text" class="input-tag form-control distinctTags iblock" id="addTags2" name="addTags2"></span>
						<span><input type="text" class="input-tag form-control distinctTags iblock" id="addTags3" name="addTags3"></span>
						<span><input type="text" class="input-tag form-control distinctTags iblock" id="addTags4" name="addTags4"></span>
					</div>

				<br>

				<div class="form-group mt60">
					<input type="submit" class="form-control" id="inputSubmit" name="inputSubmit" value="Submit">
				</div>

				<p id="outputArea" style=""></p>

			</form>
		</div><!-- end col9 -->
	</div><!-- end row -->
</div><!-- end container-fluid -->

<script src="../js/add-product.js"></script>

<?php require_once("../php/lib/footer.php"); ?>