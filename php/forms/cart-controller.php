<?php
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/order.php");
require_once("../classes/profile.php");
require_once("../classes/user.php");

// verify the form values have been submitted
//if(@isset($_POST["profileId"]) === false || @isset($_POST["tweetContent"]) === false) {
//	echo "<p class=\"alert alert-danger\">form values not complete. Verify the form and try again.</p>";
//}
//
//try {
//	//
//	mysqli_report(MYSQLI_REPORT_STRICT);
//	$mysqli = new mysqli("localhost", "--USERNAME--", "--PASSWORD--", "--DATABASE--");
//	$tweet = new Tweet(null, $_POST["profileId"], $_POST["tweetContent"]);
//	$tweet->insert($mysqli);
//	echo "<p class=\"alert alert-success\">Tweet (id = " . $tweet->getTweetId() . ") posted!</p>";
//} catch(Exception $exception) {
//	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
//}
?>