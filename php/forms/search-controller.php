<?php

session_start();

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

$searchq = filter_var($_POST["inputSearch"], FILTER_SANITIZE_STRING);

if($searchq == "") {
	echo "<p>No search term entered</p>";
}

header('Location: ../../search/index.php?searchq='. $searchq);


?>