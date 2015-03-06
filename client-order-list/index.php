<?php


// dummy session
$currentDir = dirname(__FILE__);
require_once ("../root-path.php");
require_once("../php/lib/header.php");

// classes
require_once("../php/classes/order.php");
require_once("../php/classes/orderproduct.php");
require_once("../php/classes/checkout.php");
require_once("../php/classes/location.php");
require_once("../php/classes/product.php");

$profileId = $_SESSION['profileId'];

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
		sort($orders);
		foreach($orders as $order) {
			$orderId = $order->getOrderId();
			$orderProducts = OrderProduct::getAllOrderProductsByOrderId($mysqli, $orderId);
			$checkout = Checkout::getCheckoutByOrderId($mysqli, $orderId);
			$checkoutDate = $checkout->getCheckoutDate();
			$formattedDate = $checkoutDate->format("m/d/Y - H:i:s");
			$checkoutFinalPrice = number_format((float)$checkout->getFinalPrice(), 2, '.', '');

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
				$orderProductQuantity = $orderProduct->getProductQuantity();
				$locationId = $orderProduct->getLocationId();
				$product = Product::getProductByProductId($mysqli, $productId);
				$productName = $product->getProductName();
				$productWeight = $product->getProductWeight();
				$productPrice = number_format((float)$product->getProductPrice(), 2, '.', '');
				$location = Location::getLocationByLocationId($mysqli, $locationId);
				$locationName = $location->getLocationName();

				echo "$orderProductQuantity order of $productWeight lbs. of $productName for  $$productPrice at $locationName location";
				echo '<br>';
			}
			echo '</td>' ;
			echo '</tr>';

			echo '<tr>';
			echo '<td>Final Price</td>';
			echo '<td>$'.$checkoutFinalPrice.'</td>';
			echo '</tr>';
			echo '</table>';

		}
//		echo '</table>';

	} else {
		echo '<div class="container-fluid">';
		echo '<div class="row">';
		echo 'No orders found.';
		echo '</div>';
		echo '</div>';

	}

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>
