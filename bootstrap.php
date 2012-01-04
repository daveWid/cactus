<?php

// Setup the autoloader
require_once "classes/DataMapper.php";
spl_autoload_register(array("\\DataMapper\\DataMapper", "autoload"));
