<?php


// dummy session
$currentDir = dirname(__FILE__);
require_once ("../root-path.php");

session_start();

if(!@isset($_SESSION['profileId'])) {
	header('Location: ../sign-in/index.php');
}

session_abort();

session_start();
require_once("../php/classes/profile.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

mysqli_report(MYSQLI_REPORT_STRICT);
$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

$profile = Profile::getProfileByProfileId($mysqli, $_SESSION['profileId']);
$profileType = $profile->getProfileType();

if($profileType === "m") {
	header('Location: ../new-user/index.php');
}

session_abort();


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

	 echo '<div class="container-fluid container-margin-sm transparent-form user-account">
				<div class="row">
					<div id="multi-menu" class="col-md-3 hidden-xs">
						<ul class="nav nav-pills nav-stacked">
							<li><a href="../edit-profile/index.php">Edit Profile</a></li>
							<li class="active"><a href="../client-order-list/index.php">List of Orders</a></li>
						</ul>
					</div>
					<div class="dropdown visible-xs" style="position:relative">
						<a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Menu<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="../edit-profile/index.php">Edit Profile</a></li>
							<li class="active"><a href="../client-order-list/index.php">List of Orders</a></li>
						</ul>
					</div>';


	// create table of existing stores
	if($orders !== null) {
		sort($orders);
		echo '<div class="col-sm-3 visible-xs">
						<h2>Orders</h2>
					</div>';
		echo '<div class="col-sm-9">
						<h2 class="center hidden-xs">Orders</h2>';
		echo '<div class="form-group">';
		echo '<table class="table table-responsive merchant-table">';
		foreach($orders as $order) {
			$orderId = $order->getOrderId();
			$orderProducts = OrderProduct::getAllOrderProductsByOrderId($mysqli, $orderId);
			$checkout = Checkout::getCheckoutByOrderId($mysqli, $orderId);
			$checkoutDate = $checkout->getCheckoutDate();
			$formattedDate = $checkoutDate->format("m/d/Y - H:i:s");
			$checkoutFinalPrice = number_format((float)$checkout->getFinalPrice(), 2, '.', '');
			echo '<tr>';
			echo '<th class="center visible-xs">Order #'.$orderId .'</th>';
			echo '<th class="hidden-xs">Order #'.$orderId .'</th>';
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

				$weight = $orderProductQuantity * $productWeight;

				if($weight == 1) {
					echo "$weight lb. of $productName";

				} elseif ($weight == 0) {
					if(substr($productName, -1) === 's') {
						if($orderProductQuantity == 1) {
							echo $orderProductQuantity . ' order of ' . $productName;
						} else {
							echo $orderProductQuantity . ' orders of ' . $productName;
						}
					} elseif($orderProductQuantity == 1) {
						echo $orderProductQuantity . ' ' . $productName;
					} else {
						echo $orderProductQuantity . ' ' . $productName . 's';
					}
				} else {
					echo "$weight lbs. of $productName";
				}

				echo " for $$productPrice at $locationName location";
				echo '<br>';
			}
			echo '</td>' ;
			echo '</tr>';

			echo '<tr>';
			echo '<td>Final Price</td>';
			echo '<td>$'.$checkoutFinalPrice.'</td>';
			echo '</tr><tr><td><td></td></td></tr>';
		}
		echo '</table>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	} else {
		echo '<div class="form-group center">';
		echo '<h4>No orders found.</h4>';
		echo '</div>';
	}
//	echo '</div>';
//	echo '</div>';
//	echo '</div>';

} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

require_once("../php/lib/footer.php");

?>
