<?php
if(@isset($_POST["productId"]) === true) {
	$productId = filter_input(INPUT_POST, "productId", FILTER_VALIDATE_INT);
	if($productId !== false) {
		session_start();
		$_SESSION["productId"] = $productId;
	}
}
?>