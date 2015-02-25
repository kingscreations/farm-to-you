<?php

/**
* @author Florian Goussin <florian.goussin@gmail.com>
*/

// header
$currentDir = dirname(__FILE__);
require_once '../root-path.php';
require_once '../php/lib/header.php';

// credentials
require_once '/etc/apache2/capstone-mysql/encrypted-config.php';

// model
require_once("../php/classes/product.php");
//require_once("../php/classes/user.php");
//require_once("../php/classes/profile.php");
//require_once("../php/classes/store.php");
//require_once("../php/classes/location.php");

/////////////////////////////////////////////////////////////////////////
// TODO delete this as soon as possible -> for test purpose
require_once '../dummy-session-single.php';

// path for the config file
$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";

mysqli_report(MYSQLI_REPORT_STRICT);

try {


} catch(Exception $exception) {

}

?>

<div class="row">
	<div class="col-sm-7">
		<img src="https://bootcamp-coders.cnm.edu/farm-to-you/images/product/product<?php echo '' ?>" alt=""/>
	</div>
	<div class="col-sm-5"></div>
</div>