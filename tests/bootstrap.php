<?php

// Setup Autoloading
require_once __DIR__.DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR."SplClassLoader.php";

$path = dirname(__DIR__).DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR;
$loader = new SplClassLoader("Cactus", $path);
$loader->register();

// Load up clases needed for the tests
foreach (glob("tests/classes/*.php") as $filename)
{
    include_once $filename;
}
