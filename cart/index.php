<?php

session_start();

/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

require_once("../php/classes/product.php");
require_once("../php/classes/user.php");
require_once("../php/classes/profile.php");

$currentDir = dirname(__FILE__);

// header
require_once '../root-path.php';
require_once '../php/lib/header.php';

// model
require_once '/etc/apache2/capstone-mysql/encrypted-config.php';

/////////////////////////////////////////////////////////////////////////
// TODO delete this as soon as possible -> for test purpose
require_once '../dummy-session.php';

try {
	mysqli_report(MYSQLI_REPORT_STRICT);

	// get the credentials information from the server
	$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
	$configArray = readConfig($configFile);

	// connection
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	$product1 = new Product(null, $_SESSION['profile']['id'], '../images/veggies/tomato.jpg', 'tomato', 4.0, 'organic red grape tomato', 'w', 0.3);
	$product1->insert($mysqli);

	$product2 = new Product(null, $_SESSION['profile']['id'], '../images/fruits/banana.jpg', 'banana', 0.29, 'super tasty green banana', 'w', 0.24);
	$product2->insert($mysqli);

	$mysqli->close();

} catch(Exception $exception) {
	echo "Exception: " . $exception->getMessage() . "<br/>";
	echo $exception->getFile() . ":" . $exception->getLine();
}

$_SESSION['products'] = array(
	array(
		'id' => $product1->getProductId(),
		'quantity' => 7
	),
	array(
		'id' => $product2->getProductId(),
		'quantity' => 5
	)
);
// productQuantity

/////////////////////////////////////////////////////////////////////////

?>

	<div class="row-fluid">
		<div class="col-sm-12">
			<h2>Shopping cart</h2>

			<form id="cartController" action="../php/forms/cart-controller.php" method="post" onsubmit="event.preventDefault()" novalidate>
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

						try {
							mysqli_report(MYSQLI_REPORT_STRICT);

							// get the credentials information from the server
							$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
							$configArray = readConfig($configFile);

							// connection
							$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
								$configArray["database"]);

							$maxQuantity = 15;
							$counter = 1;
							foreach($_SESSION['products'] as $productFromSession) {

								// get the product from the database
								$product = Product::getProductByProductId($mysqli, $productFromSession['id']);

								echo '<tr>';
								echo '<td><img class="thumbnail tiny-thumbnail" src="' . $product->getImagePath() . '"></td>';
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

								$quantityLimit = ($stockLimit < $maxQuantity) ? $stockLimit : $maxQuantity;

								// select box
								echo '<td><select class="product-quantity" id="product'. $counter .'-quantity" name="product'. $counter .'Quantity">';

								for($i = 0; $i < $quantityLimit; $i++) {
									if(($i + 1) === $productFromSession['quantity']) {
										echo '<option selected="selected">' . ($i + 1) . '</option>';
									} else {
										echo '<option>' . ($i + 1) . '</option>';
									}
								}

								echo '</select></td>';
								// end select box

								echo '<td id="product'. $counter .'-final-price"></td>';

								echo '</tr>';
								$counter++;
							}

							// last row
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
				<div id="outputArea"></div>
				<input type="submit" value="Continue to checkout" class="btn btn-default push-right" id="cart-validate-button">
			</form>
		</div><!-- end col-sm-12 -->
	</div><!-- end row-fluid -->

<?php require_once "../php/lib/footer.php"; ?>