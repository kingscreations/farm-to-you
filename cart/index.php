<?php

/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

// header
$currentDir = dirname(__FILE__);
require_once '../root-path.php';
require_once '../php/lib/header.php';

// model
require_once("../php/classes/product.php");
require_once("../php/classes/store.php");

// credentials
require_once '/etc/apache2/capstone-mysql/encrypted-config.php';
$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";

// errors report
mysqli_report(MYSQLI_REPORT_STRICT);

?>

<div class="container-fluid cart-product white-container" id="cart">
	<form id="cartController" action="../php/forms/cart-controller.php" method="post">
		<div class="row">
			<div class="col-sm-12">
				<!--	check if the cart is empty -->
				<?php if(@isset($_SESSION['products'])) { ?>

					<?php if(count($_SESSION['products']) === 1 ) { ?>
						<h1><?php echo count($_SESSION['products']) ?> product in your cart</h1>
					<?php } else { ?>
						<h1><?php echo count($_SESSION['products']) ?> products in your cart</h1>
					<?php } ?>
				<?php } else { ?>
					<h1>Your cart is empty</h1>
					<p><a href="<?php echo SITE_ROOT_URL ?>">Back to the home page</a></p>
					<?php exit(); ?>
				<?php } ?>
			</div>
		</div>
		<div class="row hidden-xs">
			<div class="col-sm-12">

				<?php echo generateInputTags(); ?>
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

								if($product === null) {
									continue;
								}

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
												<img class="img-responsive" src="../images/placeholder.png">
											</a></td>';
								}

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

								$maxQuantity = 99;
								$stockLimit  = $product->getStockLimit();

								if($stockLimit === null) {
									$stockLimit = $maxQuantity;
								}

								echo '<td>';

								// select box
								echo '<select class="product-quantity" id="product' . $counter . '-quantity" name="productQuantity[]" ' .
									(($stockLimit === 0) ? 'disabled' : '') .' >';
								// creating $stockLimit # of options
								for($i = 0; $i < $stockLimit; $i++) {
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
								echo '<td>
											<a id="delete-product-' . $product->getProductId() . '" class="delete-item">
												<i class="fa fa-times"></i>
											</a>
										</td>';

								echo '</tr>';
								$counter++;
							}

							// last row (hacky hacky not pretty! :))
							echo '<tr><td></td><td></td><td></td><td></td>';
							echo '<td id="total-price-label">Cart total:</td>';
							echo '<td id="total-price-result"><div class="outline"></div></td></tr>';

						} catch(Exception $exception) {
							echo "Exception: " . $exception->getMessage() . "<br/>";
							echo $exception->getFile() . ":" . $exception->getLine();
						}

						?>
					</tbody>
				</table>
			</div><!-- end col-sm-12 -->
		</div><!-- end row -->

		<div class="row visible-xs mt40">
			<div class="col-xs-12">

				<div class="panel-group t-left" id="accordion" role="tablist" aria-multiselectable="true">
					<?php

					$count = 0;
					foreach($_SESSION['products'] as $sessionProductId => $sessionProduct) {

						// get the product from the database
						$product = Product::getProductByProductId($mysqli, $sessionProductId);

						if($product === null) {
							continue;
						}

						$expanded = ($count === 0) ? 'true' : 'false';

					?>
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="heading-<?php echo $product->getProductId(); ?>">
								<div class="row">
									<div class="col-xs-10">
										<h4 class="panel-title">
											<a <?php echo ($count !== 0) ? 'class="collapsed"' : ''; ?>
												data-toggle="collapse" data-parent="#accordion"
												href="#collapse-<?php echo ($count + 1); ?>"
												aria-expanded="<?php echo $expanded; ?>"
												aria-controls="collapse-<?php echo $product->getProductId(); ?>">
												<?php echo ($count + 1) . '- ' . $product->getProductName(); ?>
											</a>
										</h4>
									</div>
									<div class="col-xs-2">
										<a id="delete-product-<?php echo $product->getProductId(); ?>" class="delete-item pull-right">
											<i class="fa fa-times"></i>
										</a>
									</div>
								</div>
							</div>
							<div id="collapse-<?php echo ($count + 1); ?>"
								  class="panel-collapse collapse <?php echo ($count === 0) ? 'in' : ''; ?>"
								  role="tabpanel" aria-labelledby="heading-<?php echo ($count + 1); ?>">
								<div class="panel-body">
									<a class=""
										href="<?php echo SITE_ROOT_URL . 'product/index.php?product=' .$product->getProductId(); ?>">
										<img class="img-responsive"
											  src="<?php echo is_file($product->getImagePath()) ? CONTENT_ROOT_URL . 'images/product/' .
											basename($product->getImagePath()) : '../images/placeholder.png'; ?>">
									</a>
									<div class="product-cart-details">
										<div class="row">
											<div class="col-xs-12">
												$<?php

												echo number_format((float)$product->getProductPrice(), 2, '.', '');
												if($product->getProductWeight() === null) {
													echo ' each';
												} elseif($product->getProductWeight() == 1) {
													echo ' for 1 lb.';
												} else {
													echo ' for ' . $product->getProductWeight() . ' lbs.';
												}

												if(@isset($_SESSION['products'][$productId]['availableQuantity'])) {
													$stockLimit = $_SESSION['products'][$productId]['availableQuantity'];
												} else {
													$stockLimit = $product->getStockLimit();
												}

												echo '<div id="available-quantity">';
												if($stockLimit < 15 && $stockLimit !== 0) {
													echo 'Only ' . $stockLimit . ' available';
												} else if($stockLimit === 0) {
													echo $stockLimit . ' available';
												} else if($stockLimit > 60) {
													echo 'A lot available';
												} else {
													echo $stockLimit .' available';
												}
												echo '</div>';

												?>
											</div><!-- end col-xs-12 -->
										</div><!-- end row -->
										<div class="row mt20">
											<div class="col-xs-6">
												<?php

												$maxQuantity = 99;
												$stockLimit  = $product->getStockLimit();

												if($stockLimit === null) {
												$stockLimit = $maxQuantity;
												}

												// select box
												echo '<select class="product-quantity" id="product' . $counter . '-quantity" name="productQuantity[]" ' .
												(($stockLimit === 0) ? 'disabled' : '') .' >';
												// creating $stockLimit # of options
												for($i = 0; $i < $stockLimit; $i++) {
													if(($i + 1) === intval($sessionProduct['quantity'])) {
														echo '<option selected="selected">' . ($i + 1) . '</option>';
													} else {
														echo '<option>' . ($i + 1) . '</option>';
													}
												}

												echo '</select>';

												?>
											</div><!-- end col-xs-6 -->
											<div class="col-xs-6">
												<div class="col-xs-5 col-xs-offset-2">
													<div class="mt20">
														Total:
													</div>
												</div>
												<div class="col-xs-4">
													<div id="product-mobile-'<?php echo $counter; ?>.'-final-price">
												</div>
												<div>
											</div><!-- end col-xs-6 -->
										</div><!-- end row -->
									</div><!-- end product cart details -->
								</div><!-- end panel body -->
							</div><!-- end collapse -->
						</div><!-- end panel-default -->
					<?php

						$count++;
					}

					?><!-- end foreach S_SESSION['products']... -->
				</div><!-- end accordion -->

			</div><!-- end col-xs-12 -->
		</div><!-- end row -->
		<div class="row mt30">
			<div class="col-xs-5 col-xs-offset-2">
				<div class="mt20">
					Total price to pay:
				</div>
			</div>
			<div class="col-xs-4">
				<div id="total-price-result" class="">
					<div class="outline"></div>
				</div>
			</div>
		</div>
		<div class="row mt40">
			<div class="col-xs-12">
				<input type="submit" value="Continue to checkout" class="btn btn-success push-right" id="cart-validate-button">
			</div>
		</div>
	</form>
	<?php

	// close the connection to mysqli
	$mysqli->close();

	?>
</div><!-- end container-fluid -->

<script src="../js/cart.js"></script>

<?php require_once "../php/lib/footer.php"; ?>