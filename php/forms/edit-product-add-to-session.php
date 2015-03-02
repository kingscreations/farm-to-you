<?php

require_once("../classes/categoryproduct.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

if(@isset($_POST["productId"]) === true) {
	$productId = filter_input(INPUT_POST, "productId", FILTER_VALIDATE_INT);
	if($productId !== false) {
		session_start();
		$_SESSION["productId"] = $productId;
		try {
			// get the credentials information from the server and connect to the database
			mysqli_report(MYSQLI_REPORT_STRICT);
			$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
			$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

			$categoryProducts = CategoryProduct::getCategoryProductByProductId($mysqli, $productId);
			$categoryProductIds = array();
			foreach($categoryProducts as $categoryProduct) {
				$categoryProductId = $categoryProduct->getCategoryId();
				$categoryProductIds[] = $categoryProductId;
			}
			$_SESSION["categoryId1"] = $categoryProductIds[0];
			$_SESSION["categoryId2"] = $categoryProductIds[1];
			$_SESSION["categoryId3"] = $categoryProductIds[2];
			$_SESSION["categoryId4"] = $categoryProductIds[3];
		} catch(Exception $exception) {
			echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
		}
	}
}
?>