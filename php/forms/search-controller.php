<?php
require_once("../classes/product.php");
require_once("../classes/store.php");
require_once("../classes/category.php");
require_once("../classes/location.php");
require_once("../classes/categoryproduct.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");



$searchq = $_POST["inputSearch"];
$searchq = preg_replace("#[^0-9a-z]#i", "", $searchq);
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



// query the database. The amount of columns have to match as it currently is. Need more from product and location though
	$result1 = mysqli_query($mysqli, "SELECT productName, productPrice, productDescription FROM product WHERE productName LIKE '%$searchq%' OR productDescription LIKE '%$searchq%'");
	$result2 = mysqli_query($mysqli,"SELECT storeName, imagePath, storeDescription  FROM store WHERE storeName LIKE '%$searchq%'");
	$result3 = mysqli_query($mysqli, "SELECT locationName, address1, city FROM location WHERE locationName LIKE '%$searchq%'");

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

// try to print a table
	print '<table border="1">';
	while($row = mysqli_fetch_array($result1)) {
		print '<tr>';
		print '<th>Product</th>';
		print '<th>Description</th>';
		print '<th>Price</th>';
		print '</tr>';
		print '<tr>';
//		print '<td>'.$row["id"].'</td>';
//		print '<td>'.$row["product_code"].'</td>';
		print '<td>' . $row["productName"] . '</td>';
		print '<td>' . $row["productDescription"] . '</td>';
		print '<td>' . $row["productPrice"] . '</td>';
		print '</tr>';
	}
print '</table>';

	print '<table border="1">';
	while($row = mysqli_fetch_array($result2)) {
		print '<tr>';
		print '<th>Store</th>';
		print '<th>Image</th>';
		print '<th>Description</th>';
		print '</tr>';
		print '<tr>';
//		print '<td>'.$row["id"].'</td>';
//		print '<td>'.$row["product_code"].'</td>';
		print '<td>' . $row["storeName"] . '</td>';
		print '<td>' . $row["imagePath"] . '</td>';
		print '<td>' . $row["storeDescription"] . '</td>';
	}
	print '</table>';

print '<table border="1">';
while($row = mysqli_fetch_array($result3)) {
	print '<tr>';
	print '<th>Location</th>';
	print '<th>Address</th>';
	print '<th>City</th>';
	print '</tr>';
	print '<tr>';
//		print '<td>'.$row["id"].'</td>';
//		print '<td>'.$row["product_code"].'</td>';
	print '<td>' . $row["locationName"] . '</td>';
	print '<td>' . $row["address1"] . '</td>';
	print '<td>' . $row["city"] . '</td>';
	print '</tr>';
}
print '</table>';