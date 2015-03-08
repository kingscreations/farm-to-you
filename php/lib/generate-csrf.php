<?php

/**
 * workaround to regenerate a new csrf token
 *
 * @author Florian Goussin <florian.goussin@gmail.com>
 */
require_once("csrf.php");
session_start();

echo generateInputTags();

?>