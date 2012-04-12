<?php

// Setup Autoloading
require_once __DIR__.DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR."SplClassLoader.php";

$parts = array(__DIR__, "..", "classes");
$path = realpath(implode(DIRECTORY_SEPARATOR, $parts)).DIRECTORY_SEPARATOR;
$loader = new SplClassLoader(null, $path);
$loader->register();

unset($parts, $path);

// Load up clases needed for the tests
foreach (glob("tests/classes/*.php") as $filename)
{
    include_once $filename;
}

define("DB_NAME", "cactus_test");

define('MYSQL_DSN', 'mysql:host=localhost;dbname='.DB_NAME.';');
define('MYSQL_USER', 'root');
define('MYSQL_PASSWORD', '');
