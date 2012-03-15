<?php

// Autoloading
require_once "classes".DIRECTORY_SEPARATOR."SplClassLoader.php";

$loader = new SplClassLoader;
$path = dirname(__FILE__).DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR;
$loader->setIncludePath($path);
$loader->register();
unset($path);