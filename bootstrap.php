<?php

// Autoloading
require_once "classes".DIRECTORY_SEPARATOR."SplClassLoader.php";

// Register the class files
$path = __DIR__.DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR;
$loader = new SplClassLoader(null, $path);
$loader->register();

// Cleanup
unset($path);
