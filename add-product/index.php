<?php
//require files for session
$currentDir = dirname(__FILE__);
require_once ("../root-path.php");

session_start();

//check if there is a store session, if not redirect
if(!@isset($_SESSION['storeId'])) {
	header('Location: ../sign-in/index.php');
}

session_abort();

//require header and classes needed
require_once("../php/lib/header.php");
require_once("../php/classes/product.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

?>

<!-- start the container-fluid to display potentials exceptions in the layout -->
<div class="container-fluid container-margin-sm transparent-form user-account" id="add-product">
	<div class="row">

<?php

try {
	// get the credentials information from the server and connect to the database
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	// grab all stores by profile id in dummy session
	$products = Product::getAllProductsByStoreId($mysqli, $_SESSION["storeId"]);

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>
		<!--side menu for navigation-->
		<div id="multi-menu" class="col-md-3 hidden-xs">
			<ul class="nav nav-pills nav-stacked">
				<li><a href="../edit-profile/index.php">Edit Profile</a></li>
				<li class="active"><a href="../add-store/index.php">Manage Stores</a></li>
				<li><a href="../merchant-order-list/index.php">List of Orders</a></li>
				<li><a href="../bank-account/index.php">Bank Account</a></li>
			</ul>
		</div>

		<div class="dropdown visible-xs" style="position:relative">
			<a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Menu<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="../edit-profile/index.php">Edit Profile</a></li>
				<li class="active"><a href="../add-store/index.php">Manage Stores</a></li>
				<li><a href="../merchant-order-list/index.php">List of Orders</a></li>
				<li><a href="../bank-account/index.php">Bank Account</a></li>
			</ul>
		</div>

		<!--Form for adding a new product-->
		<div class="col-md-6">

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

				<div class="input-tags visible-xs">
					<div class="form-group mb0">
						<label for="addTags">Tags:</label>
						<span><input type="text" class="input-tag form-control distinctTags iblock" id="addTags1" name="addTags1"></span>
						<span><input type="text" class="input-tag form-control distinctTags iblock" id="addTags2" name="addTags2"></span>
					</div>
					<div class="form-group mt0">
						<label></label>
						<span><input type="text" class="input-tag form-control distinctTags iblock" id="addTags3" name="addTags3"></span>
						<span><input type="text" class="input-tag form-control distinctTags iblock" id="addTags4" name="addTags4"></span>
					</div>
				</div>
				<div class="input-tags hidden-xs">
					<div class="form-group">
						<label for="addTags">Tags:</label>
						<span><input type="text" class="input-tag form-control distinctTags" id="addTags1" name="addTags1"></span>
						<span><input type="text" class="input-tag form-control distinctTags" id="addTags2" name="addTags2"></span>
					</div>
					<div class="form-group mt10">
						<label></label>
						<span><input type="text" class="input-tag form-control distinctTags" id="addTags3" name="addTags3"></span>
						<span><input type="text" class="input-tag form-control distinctTags" id="addTags4" name="addTags4"></span>
					</div>
				</div>
				<br>

				<div class="form-group mt60">
					<input type="submit" class="form-control" id="inputSubmit" name="inputSubmit" value="Submit">
				</div>

				<p id="outputArea" style=""></p>

			</form>

<!--			<br>-->
<!--			<div class="form-inline">-->
<!--				<button class="btn btn-default addButton" id="back">Back</button>-->
<!--			</div>-->


		</div><!-- end col6 -->
		<div class="col-md-3 hidden-xs mt60">
			<span>
				My products list
			</span>
			<ul class="unstyled" id="dynamic-product-list">
				<?php
				if($products !== null) {
					foreach($products as $index => $product) { ?>
						<li>
							<a class="product-item" href="#" id="<?php echo $product->getProductId(); ?>">
								<?php echo $product->getProductName(); ?>
							</a>
						</li>
					<?php }
				} ?>
			</ul>
		</div>
	</div><!-- end row -->
</div><!-- end container-fluid -->

<script src="../js/add-product.js"></script>

<?php require_once("../php/lib/footer.php"); ?>