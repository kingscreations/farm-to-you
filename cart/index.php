<?php
/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */
function my_session_start()
{
	if (ini_get('session.use_cookies') && isset($_COOKIE['PHPSESSID'])) {
		$sessid = $_COOKIE['PHPSESSID'];
	} elseif (!ini_get('session.use_only_cookies') && isset($_GET['PHPSESSID'])) {
		$sessid = $_GET['PHPSESSID'];
	} else {
		session_start();
		return false;
	}

	if (!preg_match('/^[a-z0-9]{32}$/', $sessid)) {
		return false;
	}
	session_start();

	return true;
}
my_session_start();


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
		'productId'        => 11,
		'productName'      => 'tomato',
		'productPrice'     => 4.0,
		'productPriceType' => 'w',
		'productType'      => 'red grappe tomato',
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
		'productType'      => 'green banana',
		'productWeight'    => 0.24, // lb
		'stockLimit'       => '1435',
		'imagePath'        => '../images/fruits/banana.jpg',
		'productQuantity'  => '7'
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

$maxQuantity = 15;

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
						$counter = 1;
						foreach($_SESSION['products'] as $product) {
							echo '<tr>';
							echo '<td><img class="thumbnail tiny-thumbnail" src="' . $product['imagePath'] . '"></td>';
							echo '<td>' . $product['productName'] . '</td>';
							echo '<td>' . $product['productPrice'] . '</td>';
							echo '<td><select name="product'. $counter .'Quantity">';
							$stockLimit = $product['stockLimit'];
							$quantityLimit = ($stockLimit < $maxQuantity) ? $stockLimit : $maxQuantity;
							for($i = 0; $i < $quantityLimit; $i++) {
								echo '<option>' . ($i + 1) . '</option>';
							}
							echo '</select></td>';
							echo '</tr>';
							echo '<input type="text" value="'. $product['productId'] .'" class="hidden" name="product'. $counter .'Id" />';
							echo '<input type="text" value="'. $product['productPrice'] .'" class="hidden" name="product'. $counter .'Price" />';
							echo '<input type="text" value="'. $product['productQuantity'] .'" class="hidden" name="product'. $counter .'Quantity" />';
							$counter++;
						}

						?>
					</tbody>
				</table>
				<input type="submit" value="Validate your cart" class="btn btn-default" id="cart-validate-button">
			</form>
		</div><!-- end col-sm-12 -->
	</div><!-- end row-fluid -->

<?php require_once "../php/lib/footer.php"; ?>