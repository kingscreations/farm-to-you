<?php
// first, require the encrypted configuration functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// now retrive the configuration parameters
try {
	$configFile = "/etc/apache2/capstone-mysql/farm-to-you.ini";
	$configArray = readConfig($configFile);
	/**
	 * $configArray will now contain:
	 *
	 * $configArray["hostname"] = mySQL hostname (localhost)
	 * $configArray["username"] = mySQL username
	 * $configArray["password"] = mySQL password (yahhhhhh!)
	 * $configArray["database"] = mySQL database
	 */
} catch (InvalidArgumentException $invalidArgument) {
	// handle (or re-throw) the exception here
}