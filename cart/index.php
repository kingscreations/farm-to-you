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
require_once("../php/classes/storelocation.php")

/////////////////////////////////////////////////////////////////////////
// TODO delete this as soon as possible -> for test purpose
require_once '../dummy-session-single.php';

// path for the config file
$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";

mysqli_report(MYSQLI_REPORT_STRICT);

try {
	// get the credentials information from the server and connect to the database
	$configArray = readConfig($configFile);

	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	$store = Store::getStoreByStoreId($mysqli, 1);

	$product1 = Product::getProductByProductId($mysqli, 1);
	$product1 = Product::getProductByProductId($mysqli, 2);

	$storeLocations = StoreLocation::getAllStoreLocationsByStoreId($mysqli, 1);

	$locations = [];

	if($storeLocations !== null) {
		foreach($storeLocations as $storeLocation) {

		}
	}

	$mysqli->close();

} catch(Exception $exception) {
	echo "Exception: " . $exception->getMessage() . "<br/>";
	echo $exception->getFile() . ":" . $exception->getLine();
}

$_SESSION['products'] = array(
	$product1->getProductId() => array(
		'quantity' => 7,
		'locations' => array(
			$location->getLocationId()
		)
	),
	$product2->getProductId() => array(
		'quantity' => 5,
		'locations' => array(
			$location->getLocationId()
		)
	)
);
/////////////////////////////////////////////////////////////////////////

// TODO add a delete button for each product

?>

	<div class="row-fluid">
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

								// get the # of options to create in the select box
								$quantityLimit = ($stockLimit < $maxQuantity) ? $stockLimit : $maxQuantity;

								// select box
								echo '<td><select class="product-quantity" id="product'. $counter .'-quantity" name="productQuantity[]">';

								// creating $quantityLimit # of options
								for($i = 0; $i < $quantityLimit; $i++) {
									if(($i + 1) === $sessionProduct['quantity']) {
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
				<p id="outputArea"></p>
				<input type="submit" value="Continue to checkout" class="btn btn-default push-right" id="cart-validate-button">
			</form>
		</div><!-- end col-sm-12 -->
	</div><!-- end row-fluid -->

<?php require_once "../php/lib/footer.php"; ?>