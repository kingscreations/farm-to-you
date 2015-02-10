<?php
/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

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
					for($i = 0; $i < sizeof($orderProducts); $i++) {
						$productId = $orderProducts[$i]->getProductId();
						$product = null;
						for($j = 0; $i < sizeof($products); $j++) {
							if($products[$j]->getProductId() === $productId) {
								$product = $products[$j];
								break;
							}
						}
						$orderId = $orderProducts[$i]->getOrderId();
						$order = null;
						for($j = 0; $i < sizeof($orders); $j++) {
							if($orders[$j]->getOrderId() === $orderId) {
								$order = $orders[$j];
								break;
							}
						}

						echo '<tr>';
						echo '<td><img src="' . $product->getImagePath() . '"></td>';
						echo '<td>' . $product->getProductName() . '</td>';
						echo '<td>' . $product->getProductPrice() . '</td>';
						echo '
								<td>
									<select>
										<option>1</option>
										<option>2</option>
										<option>3</option>
										<option>4</option>
										<option>5</option>
										<option>6</option>
										<option>7</option>
										<option>8</option>
										<option>9</option>
										<option>10</option>
										<option>11</option>
										<option>12</option>
										<option>13</option>
										<option>14</option>
										<option>15</option>
									</select>
								</td>';
						echo '</tr>';
					}

					?>
				</tbody>
			</table>
			<button type="submit" value="" class="btn btn-default" id="cart-validate-button">Validate your cart</button>
		</form>
	</div><!-- end col-sm-12 -->
</div><!-- end row-fluid -->

<?php require_once "../php/lib/footer.php"; ?>