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

//$profileId = 1;

?>

<!--<script src="../js/merchant-order-list.js"></script>-->

<div id="multi-menu" class="col-md-3 hidden-sm hidden-xs">
	<ul class="nav nav-pills nav-stacked">
		<li><a href="../edit-profile/index.php">Edit Profile</a></li>
		<li><a href="../add-store/index.php">Manage Stores</a></li>
		<li class="active"><a href="../merchant-order-list/index.php">List of Orders</a></li>
		<li class="disabled"><a href="#">Account Settings</a></li>
	</ul>
</div>

<div class="dropdown hidden-lg hidden-md" style="position:relative">
	<a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Menu<span class="caret"></span></a>
	<ul class="dropdown-menu">
		<li><a href="../edit-profile/index.php">Edit Profile</a></li>
		<li><a href="../add-store/index.php">Manage Stores</a></li>
		<li class="active"><a href="../merchant-order-list/index.php">List of Orders</a></li>
		<li class="disabled"><a href="#">Account Settings</a></li>
	</ul>
</div>

<?php

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

try {
	// get the credentials information from the server and connect to the database
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$products = Product::getAllProductsFromMerchantByProfileId($mysqli, $profileId);
	if($products !== null) {
		echo '<div class="container-fluid">';
		echo '<div class="row">';
		echo '<div class="col-md-9">';
		echo '<h2>Orders</h2>';
		echo '<br>';
		$allOrderProducts = array();
		foreach($products as $product) {
			$merchantProductId = $product->getProductId();
			$orderProducts = OrderProduct::getAllOrderProductsByProductId($mysqli, $merchantProductId);
			if($orderProducts !== null) {
				$allOrderProducts = array_merge($allOrderProducts, $orderProducts);
			}
		}

		$orders = array();
		foreach($allOrderProducts as $allOrderProduct) {
			$orderId = $allOrderProduct->getOrderId();
			$order = Order::getOrderByOrderId($mysqli, $orderId);
			$orders[] = $order;
		}
		$orders = array_unique($orders, SORT_REGULAR);

		function intcmp($a,$b)
		{
			return ($a-$b) ? ($a-$b)/abs($a-$b) : 0;
		}


		function cmp($a, $b) {
			return intcmp($a->getOrderId(), $b->getOrderId());
		}
		usort($orders, "cmp");

		if($orders !== null) {

			foreach($orders as $order) {
				$orderId = $order->getOrderId();
				$orderProducts = OrderProduct::getAllOrderProductsByOrderId($mysqli, $orderId);
				$checkout = Checkout::getCheckoutByOrderId($mysqli, $orderId);
				$checkoutDate = $checkout->getCheckoutDate();
				$formattedDate = $checkoutDate->format("m/d/Y - H:i:s");
				$checkoutFinalPrice = $checkout->getFinalPrice();

				echo '<table class="table table-responsive">';
				echo '<tr>';
				echo '<th>Order #' . $orderId . '</th>';
				echo '<th></th>';
				echo '</tr>';
				echo '<tr>';
				echo '<td>Date</td>';
				echo '<td>' . $formattedDate . '</td>';
				echo '</tr>';

				echo '<tr>';
				echo '<td>Products</td>';
				echo '<td>';
				foreach($orderProducts as $orderProduct) {
					$productId = $orderProduct->getProductId();
					$orderProductQuantity = $orderProduct->getProductQuantity();
					$locationId = $orderProduct->getLocationId();
					$product = Product::getProductByProductId($mysqli, $productId);
					$productName = $product->getProductName();
					$productWeight = $product->getProductWeight();
					$productPrice = $product->getProductPrice();
					$location = Location::getLocationByLocationId($mysqli, $locationId);
					$locationName = $location->getLocationName();

					echo "$orderProductQuantity order of $productWeight lbs. of $productName for  $$productPrice at $locationName location";
					echo '<br>';
				}
				echo '</td>';
				echo '</tr>';

				echo '<tr>';
				echo '<td>Final Price</td>';
				echo '<td>$' . $checkoutFinalPrice . '</td>';
				echo '</tr>';


				echo '</table>';

			}
		}
		echo '</div>';
		echo '</div>';
		echo '</div>';
	} else {
		echo '<div class="container-fluid">
					<div class="row">
						<div class="col-sm-9">
							<h2>Orders</h2>
							<h4>No orders found.</h4>
						</div>
					</div>
				</div>';
	}

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>
