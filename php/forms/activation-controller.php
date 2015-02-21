<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 2/21/2015
 * Time: 12:38 PM
 */
require_once("../classes/user.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
// require CSRF protection
require_once("../lib/csrf.php");

start_session();

// pull the user from the database
$user = getUserByActivation();

// populate the session with users info
$_SESSION['user'] = array(
	'id' => $user->getUserId()
);


