<?php

/**
 * @author Alonso Indacochea <alonso@hermesdevelopment.com>
 */

$currentDir = dirname(__FILE__);

// header
require_once '../root-path.php';
session_start();

if(!@isset($_SESSION['checkoutId'])) {
	header('Location: ../sign-in/index.php');
}

session_abort();
require_once '../php/lib/header.php';


// model
require_once('../php/classes/checkout.php');
require_once('../php/classes/product.php');
require_once('../php/classes/location.php');
require_once('../php/classes/orderproduct.php');

// connexion configuration
mysqli_report(MYSQLI_REPORT_STRICT);

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// get the credentials information from the server
$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";

?>

<div class="row-fluid">
	<div class="col-sm-4 col-sm-offset-4">

			<h2>Thanks for your order!</h2>

			<br>
			<?php

			try {
				// connection
				$configArray = readConfig($configFile);
				$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
					$configArray["database"]);

			} catch (Exception $exception) {
				echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
			}

			// get the active profile
			$checkout = Checkout::getCheckoutByCheckoutId($mysqli, $_SESSION['checkoutId']);
			$orderId = $checkout->getOrderId();
			$orderProducts = OrderProduct::getAllOrderProductsByOrderId($mysqli, $orderId);
			$formattedDate = $checkout->getCheckoutDate()->format("m/d/Y - H:i:s");
			$checkoutFinalPrice = number_format((float)$checkout->getFinalPrice(), 2, '.', '');

			if($checkout === null) {
				throw new Exception('Problem with the SESSION: checkout is null');
			}

			echo '<table class="table table-responsive merchant-table">';
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
					if($orderProductQuantity == 1) {
						echo $orderProductQuantity . ' ' . $productName;
					}
					echo $orderProductQuantity . ' ' . $productName . 's';
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

?>
			</table>
	</div>
</div><!-- end row-fluid -->

<script src="../js/confirmation.js"></script>

<?php require_once '../php/lib/footer.php'; ?>
