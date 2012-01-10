<?php
// Load up the Cactus autoloader
include realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."..").DIRECTORY_SEPARATOR."bootstrap.php";

// Load up clases needed for the tests
foreach (glob("tests/classes/*.php") as $filename)
{
    include $filename;
}

define("DB_NAME", "cactus_test");

define('MYSQL_DSN', 'mysql:host=localhost;dbname='.DB_NAME.';');
define('MYSQL_USER', 'root');
define('MYSQL_PASSWORD', '');
