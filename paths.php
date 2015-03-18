<?php

// content path
define('CONTENT_ROOT_URL', 'http://farmtoyou.me/');
define('CONTENT_ROOT_PATH', '/var/www/html/farm-to-you/');

define('IMAGE_PATH', 'images/');

//$rootUrl = 'http://farmtoyou.me/';
//
//define('SITE_ROOT_URL', $rootUrl);
//define('SITE_ROOT_PATH', dirname(__FILE__).'/');

$rootUrl = "http://". $_SERVER["SERVER_NAME"] . '/';

define('SITE_ROOT_URL', $rootUrl);
define('SITE_ROOT_PATH', dirname(__FILE__).'/');

//var_dump($rootUrl);
//exit();

?>