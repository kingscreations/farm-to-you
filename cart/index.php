<?php

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
require_once("../php/classes/store.php");

// path for the config file
$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";

mysqli_report(MYSQLI_REPORT_STRICT);

?>
<div class="container-fluid vertical-spacer-60">
	<div class="row">
		<div class="col-sm-12">
			<?php if(@isset($_SESSION['products'])) { ?>
				<h1><?php echo count($_SESSION['products']) ?> product in you cart</h1>
			<?php } else { ?>
				<h1>Your cart is empty</h1>
				<p><a href="<?php echo SITE_ROOT_URL ?>">Back to the home page</a></p>
			<?php } ?>
		</div>
	</div>
</div>

<?php if(@isset($_SESSION) && @isset($_SESSION['products'])) { ?>
	<div class="container-fluid cart-product white-container" id="cart">
		<div class="row">
			<div class="col-sm-12">
				<form id="cartController" action="../php/forms/cart-controller.php" method="post">
					<table class="table">
						<thead>
							<tr>
								<th></th>
								<th></th>
								<th>Price</th>
								<th>Weight</th>
								<th>Quantity</th>
								<th>Product total</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php

							try {
								// get the credentials information from the server and connect to the database
								$configArray = readConfig($configFile);

								$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
									$configArray["database"]);

								$counter = 1;

								// for each product in the cart / session
								foreach($_SESSION['products'] as $sessionProductId => $sessionProduct) {

									// get the product from the database
									$product = Product::getProductByProductId($mysqli, $sessionProductId);

									echo '<tr>';


									if(file_exists($product->getImagePath())) {
										echo '<td><a class="thumbnail" href="'. SITE_ROOT_URL . 'product/index.php?product=' .
											$product->getProductId() .'">
													<img class="img-responsive" src="' . CONTENT_ROOT_URL . 'images/product/' .
											basename($product->getImagePath()) . '">
												</a></td>';
									} else {
										echo '<td><a class="thumbnail" href="'. SITE_ROOT_URL . 'product/index.php?product=' .
											$product->getProductId() .'">
													<img class="img-responsive" src="' . $imagePlaceholderSrc . '">
												</a></td>';
									}
	//								echo '<td><img class="thumbnail tiny-thumbnail" src="' . $product->getImagePath() . '"></td>';
									echo '<td>' . $product->getProductName() . '</td>';

									// price
									echo '<td id="product'. $counter .'-price">$' . number_format($product->getProductPrice(), 2, '.', '');

									$productPriceType = $product->getProductPriceType();
									if($productPriceType === 'w') {
										echo '/lb';
									}

									echo '</td>';
									// end price

									echo '<td id="product'. $counter .'-weight">' . number_format($product->getProductWeight(), 2, '.', '') . 'lb</td>';

									$maxQuantity = 15;
									$stockLimit  = $product->getStockLimit();

									if($stockLimit === null) {
										$stockLimit = $maxQuantity;
									}

									// get the # of options to create in the select box
									$quantityLimit = ($stockLimit < $maxQuantity) ? $stockLimit : $maxQuantity;

									// select box
									echo '<td>';

									// test what kind of product we are dealing with
									echo '<select class="product-quantity" id="product' . $counter . '-quantity" name="productQuantity[]">';
									// creating $quantityLimit # of options
									for($i = 0; $i < $quantityLimit; $i++) {
										if(($i + 1) === intval($sessionProduct['quantity'])) {
											echo '<option selected="selected">' . ($i + 1) . '</option>';
										} else {
											echo '<option>' . ($i + 1) . '</option>';
										}
									}

										echo '</select>';

									echo '</td>';
									// end select box

									echo '<td id="product'. $counter .'-final-price"></td>';
									echo '<td><a id="delete-product-' . $product->getProductId() . '" class="delete-item"><i class="fa fa-times"></i></a></td>';

									echo '</tr>';
									$counter++;
								}

								// last row (hacky hacky not pretty! :))
								echo '<tr><td></td><td></td><td></td><td></td>';
								echo '<td id="total-price-label">Cart total:</td>';
								echo '<td id="total-price-result"><div class="outline"></div></td></tr>';

								$mysqli->close();

							} catch(Exception $exception) {
								echo "Exception: " . $exception->getMessage() . "<br/>";
								echo $exception->getFile() . ":" . $exception->getLine();
							}

							?>
						</tbody>
					</table>
					<p id="outputArea"></p>
					<input type="submit" value="Continue to checkout" class="btn btn-success push-right" id="cart-validate-button">
				</form>
				<div id="outputArea"></div>
			</div><!-- end col-sm-12 -->
		</div><!-- end row -->
	</div><!-- end container-fluid -->
<?php } ?>

<?php require_once "../php/lib/footer.php"; ?>