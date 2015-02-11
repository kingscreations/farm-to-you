<?php
/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

//session_start();

// header
$currentDir = dirname(__FILE__);
require_once '../root-path.php';
require_once '../php/lib/header.php';

// model
require_once '/etc/apache2/capstone-mysql/encrypted-config.php';
require_once '../php/classes/orderproduct.php';
require_once '../php/classes/product.php';
require_once '../php/classes/order.php';
require_once '../php/classes/profile.php';
require_once '../php/classes/user.php';


$_SESSION['products'] = array(
	array(
		'productName' => 'tomato',
		'productPrice' => 4.0,
		'productType' => 'red grappe tomato',
		'productWeight' => 0.3, // lb
		'stockLimit' => '56',
		'imagePath' => '../images/veggies/tomato.jpg',
		'productQuantity' => '7'
	),
	array(
		'productName' => 'banana',
		'productPrice' => 0.29,
		'productType' => 'green banana',
		'productWeight' => 0.24, // lb
		'stockLimit' => '1435',
		'imagePath' => '../images/fruits/banana.jpg',
		'productQuantity' => '7'
	)
);

try {
	mysqli_report(MYSQLI_REPORT_STRICT);

	// get the credentials information from the server
	$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
	$configArray = readConfig($configFile);

	// connection
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
		$configArray["database"]);

	$products = Product::getAllProducts($mysqli);
	$orders = Order::getAllOrders($mysqli);
	$orderProducts = OrderProduct::getAllOrderProducts($mysqli);
	$mysqli->close();

} catch(Exception $exception) {
	echo "Exception: " . $exception->getMessage() . "<br/>";
	echo $exception->getFile() .":" . $exception->getLine();
}

?>

<div class="row-fluid">
	<div class="col-sm-12">
		<h2>Shopping cart</h2>

		<form action="../php/forms/cart-controller.php" method="post">
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
					foreach($_SESSION['products'] as $product) {
						echo '<tr>';
						echo '<td><img class="thumbnail tiny-thumbnail" src="' . $product['imagePath'] . '"></td>';
						echo '<td>' . $product['productName'] . '</td>';
						echo '<td>' . $product['productPrice'] . '</td>';
						echo '<td><select>';
						$stockLimit = $product['stockLimit'];
						$quantityLimit = ($stockLimit < 15) ? $stockLimit : 15;
						for($i = 0; $i < $quantityLimit; $i++) {
							echo '<option>' . ($i + 1) . '</option>';
						}
						echo '</select></td>';
						echo '</tr>';
					}

					?>
				</tbody>
			</table>
			<input type="submit" value="" class="btn btn-default" id="cart-validate-button">Validate your cart</button>
		</form>
	</div><!-- end col-sm-12 -->
</div><!-- end row-fluid -->

<?php require_once "../php/lib/footer.php"; ?>