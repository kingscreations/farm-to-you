<?php

require_once("../../php/classes/product.php");
require_once("../../php/classes/orderproduct.php");
require_once("../../php/classes/categoryproduct.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

if(@isset($_POST["productId"]) === true) {
	$_POST["productId"] = filter_input(INPUT_POST, "productId", FILTER_VALIDATE_INT);
	if($_POST["productId"] !== false) {
		$productId = $_POST["productId"];
		try {

			// get the credentials information from the server and connect to the database
			mysqli_report(MYSQLI_REPORT_STRICT);
			$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
			$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

			$orderProducts = OrderProduct::getAllOrderProductsByProductId($mysqli, $productId);
			foreach($orderProducts as $orderProduct) {
				$orderProduct->delete($mysqli);
			}

			$categoryProducts = CategoryProduct::getCategoryProductByProductId($mysqli, $productId);
			foreach($categoryProducts as $categoryProduct) {
				$categoryProduct->delete($mysqli);
			}

			$product = Product::getProductByProductId($mysqli, $productId);
			$product->delete($mysqli);

		} catch (Exception $exception) {
			echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
		}
	}
}
?>