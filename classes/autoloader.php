<?php
namespace DataMapper;

/**
 * The DataMapper autoloader
 *
 * @package    DataMapper
 * @author     Dave Widmer <dave@davewidmer.net> 
 */
class Autoloader
{
	/**
	 * Built in class autoloading based on the PSR-0 standards.
	 *
	 * @see    https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
	 *
	 * @param  string   $class   The name of the class to autoload
	 * @return boolean           Was the class found and loaded?
	 */
	public static function load($class)
	{
		$found = false;
		$class = ltrim($class, "\\");

		if (strtolower(substr($class, 0, 10)) != "datamapper")
		{
			return false;
		}

		$base = dirname(__FILE__) . DIRECTORY_SEPARATOR;
		$file = $base . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

		if (is_file($file))
		{
			include $file;
			$found = true;
		}

		return $found;
	}

	/**
	 * Registers the auto loader
	 */
	public static function register()
	{
		spl_autoload_register(array("\\DataMapper\\Autoloader", "load"));
	}

	/**
	 * Unregisters the autoloader.
	 */
	public static function unregister()
	{
		spl_autoload_unregister(array("\\DataMapper\\Autoloader", "load"));
	}

}
