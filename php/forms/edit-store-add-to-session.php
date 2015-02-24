<?php
if(@isset($_POST["storeId"]) === true) {
	$storeId = filter_input(INPUT_POST, "storeId", FILTER_VALIDATE_INT);
	if($storeId !== false) {
		session_start();
		$_SESSION["storeId"] = $storeId;
	}
}
?>