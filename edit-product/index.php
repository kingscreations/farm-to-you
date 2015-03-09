<?php
$currentDir = dirname(__FILE__);
require_once ("../root-path.php");

session_start();

if(!@isset($_SESSION['productId'])) {
	header('Location: ../sign-in/index.php');
}

session_abort();

require_once("../php/lib/header.php");
require_once("../php/classes/product.php");
require_once("../php/classes/category.php");
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

	$category1 = Category::getCategoryByCategoryId($mysqli, $_SESSION["categoryId1"]);
	$category2 = Category::getCategoryByCategoryId($mysqli, $_SESSION["categoryId2"]);
	$category3 = Category::getCategoryByCategoryId($mysqli, $_SESSION["categoryId3"]);
	$category4 = Category::getCategoryByCategoryId($mysqli, $_SESSION["categoryId4"]);

	if($category1 !== null) {
		$categoryName1 = $category1->getCategoryName();
	} else {
		$categoryName1 = "";
	}
	if($category2 !== null) {
		$categoryName2 = $category2->getCategoryName();
	} else {
		$categoryName2 = "";
	}
	if($category3 !== null) {
		$categoryName3 = $category3->getCategoryName();
	} else {
		$categoryName3 = "";
	}
	if($category4 !== null) {
		$categoryName4 = $category4->getCategoryName();
	} else {
		$categoryName4 = "";
	}

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}
?>

<div class="container-fluid container-margin-sm user-account transparent-form" id="edit-product">
	<div class="row">

		<div id="multi-menu" class="col-md-3 hidden-sm hidden-xs">
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

		<div class="form-group edit-product mt30 mb40 visible-xs">
			<h2>Edit Product</h2>
			<figure>
				<a href="#" id="editProductImageLink" class="edit-product-image-link">
					<?php

					$imagePlaceholderSrc = CONTENT_ROOT_URL. 'images/placeholder.jpg';

					$productBaseUrl      = CONTENT_ROOT_URL . 'images/product/';
					$productBasePath     = CONTENT_ROOT_PATH . 'images/product/';
					$productImageSrc     = basename($product->getImagePath());

					// show a placeholder if the product is not associated with an image
					if(is_file($product->getImagePath())) {
						?>
						<img class="thumbnail image-preview" src="<?php echo $productBaseUrl . $productImageSrc; ?>" alt="<?php echo $productName; ?>"/>
					<?php } else { ?>
						<img class="thumbnail image-preview" src="<?php echo $productImageSrc; ?>" alt="<?php echo $productName; ?>"/>
					<?php } ?>
				</a>
			</figure>
			<figcaption>Change product image</figcaption>
		</div>

		<!--Form to edit a product-->
		<div class="col-sm-6">
			<div class="hidden-xs center">
				<h2>Edit Product</h2>
			</div>

			<form id="editProduct" class="form-inline" method="post" action="../php/forms/edit-product-controller.php"  enctype="multipart/form-data">
				<?php echo generateInputTags(); ?>
				<div class="form-group">
					<label for="editProductName">Product Name:</label>
					<input type="text" class="form-control" name="editProductName" id="editProductName" value="<?php echo $productName;?>">
				</div>

				<br>

				<div class="form-group">
					<label for="editProductPrice">Product Price: $</label>
					<input type="text" class="form-control" name="editProductPrice" id="editProductPrice" value="<?php echo $productPrice;?>">
				</div>

				<br>

				<div class="form-group">
					<label for="editProductDescription">Product Description:</label>
					<textarea class="form-control" name="editProductDescription" id="editProductDescription"><?php echo $productDescription;?></textarea>
				</div>

				<br>

<!--				<div class="form-group radio mt20 hidden-xs">-->
<!--					<label for="editProductPriceType">Sold By:</label>-->
<!--					--><?php
//						if ($productPriceType === "w") {
//							echo '<input type="radio" class="form-control" name="editProductPriceType" id="editProductPriceType" value="w" checked> By Weight';
//							echo '<input type="radio" class="form-control" name="editProductPriceType" id="editProductPriceType" value="u"> Per Unit';
//						}elseif ($productPriceType === "u"){
//							echo '<input type="radio" class="form-control" name="editProductPriceType" id="editProductPriceType" value="w"> By Weight';
//							echo '<input type="radio" class="form-control" name="editProductPriceType" id="editProductPriceType" value="u" checked> Per Unit';
//						}
//					?>
<!--				</div>-->

				<div class="form-group radio mt20 hidden-xs">
					<label for="editProductPriceType">Sold By:</label>
					<input type="radio" class="form-control" name="editProductPriceType" id="editProductPriceType" value="w"<?php echo ($productPriceType === 'w') ? 'checked' : '';?>> By Weight
					<input type="radio" class="form-control" name="editProductPriceType" id="editProductPriceType" value="u"> Per Unit
				</div>

				<div class="mt20 visible-xs">
					<label for="editProductPriceType">Sold By:</label>
					<input type="radio" name="editProductPriceType" id="editProductPriceType" value="w"<?php echo ($productPriceType === 'w') ? 'checked' : '';?>> By Weight
					<input class="ml1" type="radio" name="editProductPriceType" id="editProductPriceType" value="u"> Per Unit
				</div>


				<br>

				<div class="form-group">
					<label for="editProductWeight">Product Weight (lbs.):</label>
					<input type="text" class="form-control" name="editProductWeight" id="editProductWeight" value="<?php echo $productWeight;?>">
				</div>

				<br>

				<div class="form-group">
					<label for="editStockLimit">Current Stock Amount:</label>
					<input type="number" class="form-control" name="editStockLimit" id="editStockLimit" step="1" value="<?php echo $productStockLimit;?>">
				</div>

				<br>

				<div class="form-group hidden">
					<label for="editProductImage">Product Image:</label>
					<input type="file" class="form-control hidden" name="editProductImage" id="editProductImage">
				</div>
				<br>

				<div class="input-tags hidden-xs">
					<div class="form-group">
						<label for="addTags">Tags:</label>
						<span><input type="text" class="input-tag form-control distinctTags" id="addTags1" name="addTags1" value="<?php echo $categoryName1;?>"></span>
						<span><input type="text" class="input-tag form-control distinctTags" id="addTags2" name="addTags2" value="<?php echo $categoryName2;?>"></span>
					<!-- TODO mvp SUPER dirty workaround -->
					</div>
					<div class="form-group mt10">
						<label></label>
						<span><input type="text" class="input-tag form-control distinctTags" id="addTags3" name="addTags3" value="<?php echo $categoryName3;?>"></span>
						<span><input type="text" class="input-tag form-control distinctTags" id="addTags4" name="addTags4" value="<?php echo $categoryName4;?>"></span>
					</div>
				</div>

				<div class="input-tags visible-xs">
					<div class="form-group mb0">
						<label for="addTags">Tags:</label>
						<input type="text" class="input-tag form-control distinctTags iblock" id="addTags1" name="addTags1" value="<?php echo $categoryName1;?>">
						<input type="text" class="input-tag form-control distinctTags iblock" id="addTags2" name="addTags2" value="<?php echo $categoryName2;?>">
						<!-- TODO mvp dirty workaround -->
					</div>
					<div class="form-group mt0">
						<label></label>
						<input type="text" class="input-tag form-control distinctTags iblock" id="addTags3" name="addTags3" value="<?php echo $categoryName3;?>">
						<input type="text" class="input-tag form-control distinctTags iblock" id="addTags4" name="addTags4" value="<?php echo $categoryName4;?>">
					</div>
				</div>

				<div class="form-group mt60">
					<input type="submit" class="form-control" id="editSubmit" name="editSubmit" value="Submit">
				</div>

				<p id="outputArea" style=""></p>

				<br>

				<div class="form-group">
					<button class="btn btn-default editProductButton" id="back">Back</button>
				</div>
			</div><!-- end col-sm-6 -->

			<div class="col-sm-3 hidden-xs">
				<div class="form-group mt30">
					<button id="<?php echo $_SESSION['productId'];?>" class="btn btn-default linkProduct">Link to product page</button>
				</div>

				<div class="form-group edit-product mt30">
					<a href="#" id="editProductImageLink" class="edit-product-image-link">
						<figure>
							<?php

							$imagePlaceholderSrc = CONTENT_ROOT_URL. 'images/placeholder.jpg';

							$productBaseUrl      = CONTENT_ROOT_URL . 'images/product/';
							$productBasePath     = CONTENT_ROOT_PATH . 'images/product/';
							$productImageSrc     = basename($product->getImagePath());

							// show a placeholder if the product is not associated with an image
							if(is_file($product->getImagePath())) {
								?>
								<img class="thumbnail image-preview" src="<?php echo $productBaseUrl . $productImageSrc; ?>" alt="<?php echo $productName; ?>"/>
							<?php } else { ?>
								<img class="thumbnail image-preview" src="<?php echo $productImageSrc; ?>" alt="<?php echo $productName; ?>"/>
							<?php } ?>
							<figcaption>Change product image</figcaption>
						</figure>
					</a>
				</div>
			</div>

			</form>
		</div>
	</div>
</div><!-- end container-fluid -->

<!-- JS for the page-->
<script src="../js/edit-product.js"></script>

<?php require_once("../php/lib/footer.php"); ?>
