<?php

session_start();

/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

require_once("../classes/product.php");

// header
$currentDir = dirname(__FILE__);
require_once '../root-path.php';
require_once '../php/lib/header.php';

// model
require_once '/etc/apache2/capstone-mysql/encrypted-config.php';

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

$products = $_SESSION['products'];
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

						foreach($products as $product) {
							try {
								mysqli_report(MYSQLI_REPORT_STRICT);

								// get the credentials information from the server
								$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
								$configArray = readConfig($configFile);

								// connection
								$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
									$configArray["database"]);

								// user, profile and product
								// TODO delete this as soon as possible -> for test purpose
								///////////////////////////////////
								$this->user = new User(null, "test2@test.com", 'Aa10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB0BC99AB10BC99AC99AB0BC99AB10BC99AB10BC99AB1010', '99Aa10BC99AB10BC99AB10BC99AB10BC', '99Aa10BC99AB10BC');
								$this->user->insert($this->mysqli);

								$this->profile = new Profile(null, 'toto', 'sinatra', '505 986700798', 'm', 'kj', 'images/toto.jpg',
									$this->user->getUserId());
								$this->profile->insert($this->mysqli);

								$product = new Product(null, $this->profile->getProfileId(), $product['productName'],
									$product['productPrice'], $product['productPrice'], $product['productDescription'], $product['productPriceType'],
									$product['productWeight']);
								////////////////////////////////////

								$mysqli->close();

							} catch(Exception $exception) {
								echo "Exception: " . $exception->getMessage() . "<br/>";
								echo $exception->getFile() . ":" . $exception->getLine();
							}


							echo '<tr>';
							echo '<td><img class="thumbnail tiny-thumbnail" src="' . $product['imagePath'] . '"></td>';
							echo '<td>' . $product['productName'] . '</td>';
							echo '<td>' . $product['productPrice'] . '</td>';
							echo '<td><select id="product'. $counter .'Quantity" name="product'. $counter .'Quantity">';
							$stockLimit = $product['stockLimit'];
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