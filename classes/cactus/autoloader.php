<?php

namespace Cactus;

/**
 * Autoloading Cactus classes.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net> 
 */
class Autoloader
{
	/**
	 * @var  string  The base path for file loading.
	 */
	public static $path = null;

	/**
	 * Built in class autoloading based on the PSR-0 standards.
	 *
	 * @see    https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
	 *
	 * @param  string   $class   The name of the class to autoload
	 */
	public static function load($class)
	{
		$class = ltrim($class, "\\");

		if (strtolower(substr($class, 0, 6)) != "cactus")
		{
			return false;
		}

		$file = static::$path . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

		if (is_file($file))
		{
			include $file;
		}
	}

	/**
	 * Registers the auto loader
	 */
	public static function register()
	{
		spl_autoload_register(array(__CLASS__, "load"));
		static::$path = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."..") . DIRECTORY_SEPARATOR;
	}

	/**
	 * Unregisters the autoloader.
	 */
	public static function unregister()
	{
		spl_autoload_unregister(array(__CLASS__, "load"));
	}

}
