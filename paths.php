<?php

// content path
define('CONTENT_ROOT_URL', 'http://farmtoyou.me/');
define('CONTENT_ROOT_PATH', '/var/www/html/farm-to-you/');

define('IMAGE_PATH', 'images/');

// get the root url and define a constant from it
//$currentPathExploded = explode("/", $_SERVER["PHP_SELF"]);
//if(empty($currentPathExploded)) {
//	throw new RangeException('Impossible to explode the path');
//}

//$rootUrl = "http://". $_SERVER["SERVER_NAME"] . '/' . $currentPathExploded[1] . '/' .
//	$currentPathExploded[2] . '/';

$rootUrl = 'http://farmtoyou.me/';

define('SITE_ROOT_URL', $rootUrl);
define('SITE_ROOT_PATH', dirname(__FILE__).'/');

//var_dump($rootUrl);
//exit();

?>