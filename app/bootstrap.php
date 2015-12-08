<?php

/**
 * Bootstrap of the game
 */

//TODO: remove, used for debugging
putenv("APPLICATION_ENV=development");

/**
 * Shortcut for directory separator
 * @var string
 */
defined("DS") || define("DS", DIRECTORY_SEPARATOR);
/**
 * Application root path
 * @var string
 */
defined("BASEPATH") || define("BASEPATH", dirname(__DIR__) . DS);
/**
 * Where application lives. This is current file's path.
 * @var string
 */
defined("APPPATH") || define("APPPATH", __DIR__ . DS);

/**
 * Are we on development or on production server
 * @var string
 */
defined("APPLICATION_ENV") || define("APPLICATION_ENV", in_array(getenv("APPLICATION_ENV"), ["development", "production"]) ? getenv("APPLICATION_ENV") : "production");
/**
 * Define DEBUG flag
 * @var boolean
 */
defined("DEBUG") || define("DEBUG", (APPLICATION_ENV == "development"));
/**
 * Set error reporting level
 * Error reporting depends of DEBUG
 */
error_reporting((DEBUG) ? E_ALL | E_STRICT : E_ALL ^ E_NOTICE ^ E_USER_NOTICE ^ E_WARNING ^ E_DEPRECATED);
/**
 * Display errors
 */
ini_set("display_errors", DEBUG);
//TODO: add logger
// Set the default time zone
date_default_timezone_set("Europe/Sofia");
// Composer autoloader
require BASEPATH . "vendor/autoload.php";

if (php_sapi_name() == "cli") {
	//cli
	require APPPATH . 'cli.php';
} else {
	//web 
	require APPPATH . 'web.php';
}
