<?php
$currentDir = dirname(__FILE__);

require_once '../../root-path.php';
require_once("../lib/header.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/product.php");
require_once("../classes/store.php");
require_once("../classes/category.php");
require_once("../classes/location.php");
require_once("../classes/categoryproduct.php");
require_once("../lib/footer.php");



$searchq = $_POST["inputSearch"];
$searching = $_POST["searching"];

// this is only displayed if they have submitted the form
if ($searching =="yes") {
	echo "<h2>Results</h2><p>";

	// if they did not enter a search term we give them an error
	if($searchq == "") {
		echo "<p>No search term entered</p>";
		exit;
	}
}

// connect to database and filter search
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

// a bit of filtering
$searchq = strtoupper($searchq);
$searchq = strtolower($searchq);
$searchq = strip_tags($searchq);
$searchq = trim ($searchq);
$searchq = filter_var($searchq, FILTER_SANITIZE_STRING);
$searchq = escapeshellcmd($searchq);


// query the database. The amount of columns have to match as it currently is. Need more from product and location though
	$result1 = mysqli_query($mysqli, "SELECT productName, productPrice, productDescription FROM product WHERE productName LIKE '%$searchq%' OR productDescription LIKE '%$searchq%'");
	$result2 = mysqli_query($mysqli,"SELECT storeName, imagePath, storeDescription  FROM store WHERE storeName LIKE '%$searchq%'");
	$result3 = mysqli_query($mysqli, "SELECT locationName, address1, city FROM location WHERE locationName LIKE '%$searchq%' OR address1 LIKE '%$searchq%'");

// check for errors in the search
if (!$result1) {
	printf("Error: %s\n", mysqli_error($mysqli));
	exit();
}
if (!$result2) {
	printf("Error: %s\n", mysqli_error($mysqli));
	exit();
}
if (!$result3) {
	printf("Error: %s\n", mysqli_error($mysqli));
	exit();
}

// try to echo a table per each table searched by
if(mysqli_num_rows($result1) > 0 || mysqli_num_rows($result2) > 0 || mysqli_num_rows($result3) > 0) {
	echo '<table id="searchResults" class="table table-responsive">';
}
if(mysqli_num_rows($result1) > 0) {
	echo '<tr>';
	echo '<th>Product</th>';
	echo '<th>Description</th>';
	echo '<th>Price</th>';
	echo '</tr>';
	while($row = mysqli_fetch_array($result1)) {
		echo '<tr>';
		echo '<td>' . $row["productName"] . '</td>';
		echo '<td>' . $row["productDescription"] . '</td>';
		echo '<td>' . $row["productPrice"] . '</td>';
		echo '</tr>';
	}
}

if(mysqli_num_rows($result2) > 0) {
	echo '<tr>';
	echo '<th>Store</th>';
	echo '<th>Image</th>';
	echo '<th>Description</th>';
	echo '</tr>';
	while($row = mysqli_fetch_array($result2)) {
		echo '<tr>';
		echo '<td>' . $row["storeName"] . '</td>';
		echo '<td>' . $row["imagePath"] . '</td>';
		echo '<td>' . $row["storeDescription"] . '</td>';
	}
}

if(mysqli_num_rows($result3) > 0 ) {
	echo '<tr>';
	echo '<th>Location</th>';
	echo '<th>Address</th>';
	echo '<th>City</th>';
	echo '</tr>';
	while($row = mysqli_fetch_array($result3)) {
		echo '<tr>';
		echo '<td>' . $row["locationName"] . '</td>';
		echo '<td>' . $row["address1"] . '</td>';
		echo '<td>' . $row["city"] . '</td>';
		echo '</tr>';
	}
}

if(mysqli_num_rows($result1) > 0 || mysqli_num_rows($result2) > 0 || mysqli_num_rows($result3) > 0) {
	echo '</table>';
}


//this counts the number or results - and if there wasn't any it gives them a little message explaining that
if (mysqli_num_rows($result1) == 0 && mysqli_num_rows($result2) == 0 && mysqli_num_rows($result3) == 0)
{
	echo "<p class=\"alert alert-danger\">Sorry, but we can not find an entry to match your query</p><br><br>";
//and we remind them what they searched for
	echo "<b>Searched For:</b> " .$searchq;
}

?>