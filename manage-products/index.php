<?php
/**
 * manage / view all the products with edit / delete buttons
 *
 */


$currentDir = dirname(__FILE__);
require_once ("../root-path.php");

session_start();

if(!@isset($_SESSION['storeId'])) {
	header('Location: ../sign-in/index.php');
}

session_abort();

require_once("../php/lib/header.php");

require_once("../php/classes/product.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

?>

<div class="container-fluid container-margin-sm transparent-form">
	<div class="row">
		<div class="col-md-9" id="productEdit">
			<?php

			try {
				// get the credentials information from the server and connect to the database
				mysqli_report(MYSQLI_REPORT_STRICT);
				$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
				$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

				// grab all stores by profile id in dummy session
				$products = Product::getAllProductsByStoreId($mysqli, $_SESSION["storeId"]);

				// create table of existing stores
				if($products !== null) {

					echo '<table class="table table-responsive">';
					echo '<tr>';
					echo '<th>Product</th>';
					echo '<th></th>';
					echo '</tr>';
					foreach($products as $product) {
						$productId = $product->getProductId();
						$productName = $product->getProductName();
						echo '<tr>';
						echo '<td>'. $productName . '</td>';

						echo '<td><button id="'.$productId.'" class="btn btn-default editButton">Edit</button></td>';
						echo '<td><button id="'.$productId.'" class="btn btn-default deleteProductButton">Delete</button></td>';
						echo '</tr>';
					}
					echo '</table>';
				}

			} catch(Exception $exception) {
				echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
			}

			?>

			<div class="form-group">
				<button class="btn btn-default addButton" id="back">Back</button>
			</div>
		</div><!-- col-md-9 -->
	</div><!-- row -->
</div><!-- container-fluid -->

<script src="../js/manage-products.js"></script>

<?php require_once("../php/lib/footer.php"); ?>