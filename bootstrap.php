<?php

// Autoloading
require_once "classes".DIRECTORY_SEPARATOR."SplClassLoader.php";

// Register the class files
$segments = array(dirname(__FILE__), "classes");
$path = implode(DIRECTORY_SEPARATOR, $segments).DIRECTORY_SEPARATOR;
$loader = new SplClassLoader("Cactus", $path);
$loader->register();

// And autoload the vendor folder too
array_pop($segments);
$segments[] = "vendor";
$path = implode(DIRECTORY_SEPARATOR, $segments).DIRECTORY_SEPARATOR;

$vendor = new SplClassLoader(null, $path);
$vendor->register();

unset($path, $segments);
