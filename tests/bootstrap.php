<?php
// Load up the autoloader
include realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."..").DIRECTORY_SEPARATOR."bootstrap.php";

define("DB_NAME", "cactus_test");

define('MYSQL_DSN', 'mysql:host=localhost;dbname='.DB_NAME.';');
define('MYSQL_USER', 'root');
define('MYSQL_PASSWORD', '');
