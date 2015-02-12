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

// for test purpose
require_once '../php/lib/dummy-session.php';

$_SESSION['products'] = array(
	array(
		'productId'        => 11,
		'productName'      => 'tomato',
		'productPrice'     => 4.0,
		'productPriceType' => 'w',
		'productDescription'      => 'organic red grape tomato',
		'productWeight'    => 0.3, // lb
		'stockLimit'       => '56',
		'imagePath'        => '../images/veggies/tomato.jpg',
		'productQuantity'  => '7'
	),
	array(
		'productId'        => 12,
		'productName'      => 'banana',
		'productPrice'     => 0.29,
		'productPriceType' => 'w',
		'productDescription'      => 'super tasty green banana',
		'productWeight'    => 0.24, // lb
		'stockLimit'       => '1435',
		'imagePath'        => '../images/fruits/banana.jpg',
		'productQuantity'  => '7'
	)
);

$sessionProducts = $_SESSION['products'];
$sessionProfiles = $_SESSION['profiles'];
$maxQuantity = 15;

?>

	<div class="row-fluid">
		<div class="col-sm-12">
			<h2>Shopping cart</h2>

			<form id="cartController" action="../php/forms/cart-controller.php" method="post" novalidate>
				<table class="table">
					<thead>
						<tr>
							<th></th>
							<th></th>
							<th>price</th>
							<th>quantity</th>
						</tr>
					</thead>
					<tbody>
						<?php

						$productQuantities = [];
						$counter = 1;

						foreach($sessionProducts as $sessionProduct) {
							try {
								mysqli_report(MYSQLI_REPORT_STRICT);

								// get the credentials information from the server
								$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
								$configArray = readConfig($configFile);

								// connection
								$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
									$configArray["database"]);

								Product::getAllProducts()

								// user, profile and product
								// TODO delete this as soon as possible -> for test purpose
								///////////////////////////////////
								$product = new Product(null, $sessionProfiles['id'], $product['productName'],
									$product['productPrice'], $product['productPrice'], $product['productDescription'], $product['productPriceType'],
									$product['productWeight']);
								$product->insert($mysqli);
								////////////////////////////////////

								$mysqli->close();

							} catch(Exception $exception) {
								echo "Exception: " . $exception->getMessage() . "<br/>";
								echo $exception->getFile() . ":" . $exception->getLine();
							}


							echo '<tr>';
							echo '<td><img class="thumbnail tiny-thumbnail" src="' . $sessionProduct['imagePath'] . '"></td>';
							echo '<td>' . $sessionProduct['productName'] . '</td>';
							echo '<td>' . $sessionProduct['productPrice'] . '</td>';
							echo '<td><select id="product'. $counter .'Quantity" name="product'. $counter .'Quantity">';
							$stockLimit = $sessionProduct['stockLimit'];
							$quantityLimit = ($stockLimit < $maxQuantity) ? $stockLimit : $maxQuantity;
							for($i = 0; $i < $quantityLimit; $i++) {
								echo '<option>' . ($i + 1) . '</option>';
							}
							echo '</select></td>';
							echo '</tr>';
							$counter++;
						}

						?>
					</tbody>
				</table>
				<div id="outputArea"></div>
				<input type="submit" value="Validate your cart" class="btn btn-default" id="cart-validate-button">
			</form>
		</div><!-- end col-sm-12 -->
	</div><!-- end row-fluid -->

<?php require_once "../php/lib/footer.php"; ?>