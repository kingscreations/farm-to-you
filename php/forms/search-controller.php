<?php
require_once("../classes/product.php");
require_once("../classes/category.php");
require_once("../classes/categoryproduct.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

if(@isset($_POST["inputSubmit"]) && @isset($_POST["inputSearch"]) != "") {
	echo "<p class=\"alert alert-danger\">Form values not complete. Verify the form and try again.</p>";
}
