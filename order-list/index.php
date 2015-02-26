<?php


// dummy session
$currentDir = dirname(__FILE__);
require_once ("../root-path.php");
require_once("../php/lib/header.php");

// classes
require_once("../php/classes/order.php");
require_once("../php/classes/orderproduct.php");
require_once("../php/classes/product.php");

$profileId = 1;

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

try {
	// get the credentials information from the server and connect to the database
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	// grab all stores by profile id in dummy session
	$orders = Order::getAllOrdersByProfileId($mysqli, $profileId);

	// create table of existing stores
	if($orders !== null) {

		foreach($orders as $order) {
			$orderId = $order->getOrderId();
			$orderDate = $order->getOrderDate();
			$formattedDate = $orderDate->format("Y-m-d H:i:s");
			$orderProducts = OrderProduct::getAllOrderProductsByOrderId($mysqli, $orderId);
			echo '<table class="table table-responsive">';
			echo '<tr>';
			echo '<th>Order #'.$orderId .'</th>';
			echo '<th></th>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>Date</td>';
			echo '<td>'.$formattedDate .'</td>';
			echo '</tr>';


			echo '<tr>';
			echo '<td>Products</td>';
			echo '<td>' ;
			foreach($orderProducts as $orderProduct) {
				$productId = $orderProduct->getProductId();
				$product = Product::getProductByProductId($mysqli, $productId);
				$productName = $product->getProductByProductName();
				echo "'.$productName .'\n";
			}
			echo '</td>' ;
			echo '<td>'.$formattedDate .'</td>';
			echo '</tr>';
			echo '</table>';

		}
//		echo '</table>';

	} else {
		echo 'No orders found.';
	}

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>
