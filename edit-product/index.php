<?php
$currentDir = dirname(__FILE__);
require_once ("../root-path.php");
require_once("../php/lib/header.php");
//require_once("../dummy-session-single.php");
require_once("../php/classes/product.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

try {


	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);


	$product = Product::getProductByProductId($mysqli, $_SESSION['productId']);

	$productId = $product->getProductId();
	$productName = $product->getProductName();
	$productPrice = $product->getProductPrice();
	$productImagePath = $product->getImagePath();
	$productDescription = $product->getProductDescription();
	$productWeight = $product->getProductWeight();
	$productStockLimit = $product->getStockLimit();
	$productPriceType = $product->getProductPriceType();


} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}
?>
	<!-- JS for the page-->
	<script src="../js/edit-product.js"></script>

<div id="multi-menu" class="col-md-3 hidden-sm hidden-xs">
	<ul class="nav nav-pills nav-stacked">
		<li><a href="../edit-profile/index.php">Edit Profile</a></li>
		<li class="active"><a href="../add-store/index.php">Manage Stores</a></li>
		<li><a href="../merchant-order-list/index.php">List of Orders</a></li>
		<li class="disabled"><a href="#">Account Settings</a></li>
	</ul>
</div>

<div class="dropdown hidden-lg hidden-md" style="position:relative">
	<a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Menu<span class="caret"></span></a>
	<ul class="dropdown-menu">
		<li><a href="../edit-profile/index.php">Edit Profile</a></li>
		<li class="active"><a href="../add-store/index.php">Manage Stores</a></li>
		<li><a href="../merchant-order-list/index.php">List of Orders</a></li>
		<li class="disabled"><a href="#">Account Settings</a></li>
	</ul>
</div>

<!--Form to edit a product-->
<div class="col-md-9">
	<h2>Edit Product</h2>

	<form id="editProduct" class="form-inline" method="post" action="../php/forms/edit-product-controller.php"  enctype="multipart/form-data">

		<div class="form-group">
			<label for="editProductName">Product Name:</label>
			<input type="text" class="form-control" name="editProductName" id="editProductName" value="<?php echo $productName;?>">
		</div>

		<br>

		<div class="form-group">
			<label for="editProductPrice">Product Price:</label>
			<input type="text" class="form-control" name="editProductPrice" id="editProductPrice" value="<?php echo $productPrice;?>">
		</div>

		<br>

		<div class="form-group">
			<label for="editProductDescription">Product Description:</label>
			<input type="text" class="form-control" name="editProductDescription" id="editProductDescription" value="<?php echo $productDescription;?>"></textarea>
		</div>

		<br>

		<div class="form-group">
			<label for="editProductPriceType">Product Price Type:</label>
<!--			<input type="radio" class="form-control" name="editProductPriceType" id="editProductPriceType" value="w" checked>By Weight-->
<!--			<input type="radio" class="form-control" name="editProductPriceType" id="editProductPriceType" value="u">Per Unit-->
			<?php
				if ($productPriceType === "w") {
					echo '<input type="radio" class="form-control" name="editProductPriceType" id="editProductPriceType" value="w" checked>By Weight';
					echo '<input type="radio" class="form-control" name="editProductPriceType" id="editProductPriceType" value="u">Per Unit';
				}elseif ($productPriceType === "u"){
					echo '<input type="radio" class="form-control" name="editProductPriceType" id="editProductPriceType" value="w">By Weight';
					echo '<input type="radio" class="form-control" name="editProductPriceType" id="editProductPriceType" value="u" checked>Per Unit';
				}
			?>
		</div>

		<br>

		<div class="form-group">
			<label for="editProductWeight">Product Weight:</label>
			<input type="text" class="form-control" name="editProductWeight" id="editProductWeight" value="<?php echo $productWeight;?>">
		</div>

		<br>

		<div class="form-group">
			<label for="editStockLimit">Current Stock Amount:</label>
			<input type="number" class="form-control" name="editStockLimit" id="editStockLimit" step="1" value="<?php echo $productStockLimit;?>">
		</div>

		<br>

		<div class="form-group">
			<label for="editProductImage">Product Image:</label>
			<input type="file" class="form-control" name="editProductImage" id="editProductImage">
		</div>

		<br>

		<div class="form-group">
			<button id="<?php echo $_SESSION['productId'];?>" class="btn btn-default linkProduct">Link to product page</button>
		</div>
		<br>

		<div class="form-group">
			<input type="submit" class="form-control" id="editSubmit" name="editSubmit" value="Submit">
		</div>

	</form>

	<p id="outputArea" style=""></p>


	<div class="form-group">
		<button class="btn btn-default addButton" id="back">Back</button>
	</div>
</div>


	<?php
require_once("../php/lib/footer.php")
?>
