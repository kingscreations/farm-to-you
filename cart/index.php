<?php

//session_start();

/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

// header
$currentDir = dirname(__FILE__);
require_once '../root-path.php';
require_once '../php/lib/header.php';

// credentials
require_once '/etc/apache2/capstone-mysql/encrypted-config.php';

// model
require_once("../php/classes/product.php");
require_once("../php/classes/user.php");
require_once("../php/classes/profile.php");
require_once("../php/classes/store.php");
require_once("../php/classes/location.php");
require_once("../php/classes/storelocation.php");

// path for the config file
$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";

mysqli_report(MYSQLI_REPORT_STRICT);

try {
	// get the credentials information from the server and connect to the database
	$configArray = readConfig($configFile);

	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	$user     = User::getUserByUserId($mysqli, 1);
	$profile  = Profile::getProfileByProfileId($mysqli, 1);
	$store    = Store::getStoreByStoreId($mysqli, 1);

	$location = Location::getLocationByLocationId($mysqli, 1);

	$mysqli->close();

} catch(Exception $exception) {
	echo "Exception: " . $exception->getMessage() . "<br/>";
	echo $exception->getFile() . ":" . $exception->getLine();
}

// TODO add a delete button for each product

?>

<div class="container-fluid white-container">
	<div class="row">
		<div class="col-sm-12">
			<h2>Shopping cart</h2>

			<form id="cartController" action="../php/forms/cart-controller.php" method="post">
				<table class="table">
					<thead>
						<tr>
							<th></th>
							<th></th>
							<th>weight</th>
							<th>price</th>
							<th>quantity</th>
							<th>final price</th>
						</tr>
					</thead>
					<tbody>
						<?php
//						var_dump($_SESSION['products']);
						try {
							// get the credentials information from the server and connect to the database
							$configArray = readConfig($configFile);

							$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
								$configArray["database"]);

							$maxQuantity = 15;
							$counter = 1;
							foreach($_SESSION['products'] as $sessionProductId => $sessionProduct) {

								// get the product from the database
								$product = Product::getProductByProductId($mysqli, $sessionProductId);

								echo '<tr>';


								if(file_exists($product->getImagePath())) {
									echo '<td><a class="thumbnail" href="'. SITE_ROOT_URL . 'product/index.php?product=' . $product->getProductId() .'">
												<img class="img-responsive" src="' . CONTENT_ROOT_URL . 'images/product/' . basename($product->getImagePath()) . '">
											</a></td>';
								} else {
									echo '<td><a class="thumbnail" href="'. SITE_ROOT_URL . 'product/index.php?product=' . $product->getProductId() .'">
												<img class="img-responsive" src="' . $imagePlaceholderSrc . '">
											</a></td>';
								}
//								echo '<td><img class="thumbnail tiny-thumbnail" src="' . $product->getImagePath() . '"></td>';
								echo '<td>' . $product->getProductName() . '</td>';
								echo '<td id="product'. $counter .'-weight">' . $product->getProductWeight() . '</td>';

								// price
								echo '<td id="product'. $counter .'-price">$' . $product->getProductPrice();

								$productPriceType = $product->getProductPriceType();
								if($productPriceType === 'w') {
									echo '/lb';
								}

								echo '</td>';
								// end price

								$stockLimit = $product->getStockLimit();

								if($stockLimit === null) {
									$stockLimit = 15;
								}

								// get the # of options to create in the select box
								$quantityLimit = ($stockLimit < $maxQuantity) ? $stockLimit : $maxQuantity;

								// select box
								echo '<td>';

								// test what kind of product we are dealing with
								echo '<select class="product-quantity" id="product' . $counter . '-quantity" name="productQuantity[]">';
								// creating $quantityLimit # of options
								for($i = 0; $i < $quantityLimit; $i++) {
									if(($i + 1) === $sessionProduct['quantity']) {
										echo '<option selected="selected">' . ($i + 1) . '</option>';
									} else {
										echo '<option>' . ($i + 1) . '</option>';
									}
								}

									echo '</select>';

								echo '</td>';
								// end select box

								echo '<td id="product'. $counter .'-final-price"></td>';

								echo '</tr>';
								$counter++;
							}

							// last row (hacky hacky not pretty! :))
							echo '<tr><td></td><td></td><td></td><td></td>';
							echo '<td id="total-price-label">Total:</td>';
							echo '<td id="total-price-result"></td></tr>';

							$mysqli->close();

						} catch(Exception $exception) {
							echo "Exception: " . $exception->getMessage() . "<br/>";
							echo $exception->getFile() . ":" . $exception->getLine();
						}

						?>
					</tbody>
				</table>
				<p id="outputArea"></p>
				<input type="submit" value="Continue to checkout" class="btn btn-default push-right" id="cart-validate-button">
			</form>
		</div><!-- end col-sm-12 -->
	</div><!-- end row -->
</div><!-- end container-fluid -->

<?php require_once "../php/lib/footer.php"; ?>