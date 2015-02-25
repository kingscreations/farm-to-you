<?php
if(@isset($_POST["locationId"]) === true) {
	$locationId = filter_input(INPUT_POST, "locationId", FILTER_VALIDATE_INT);
	if($locationId !== false) {
		session_start();
		$_SESSION["locationId"] = $locationId;
	}
}
?>