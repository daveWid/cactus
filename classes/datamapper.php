<?php
namespace DataMapper;

/**
 * The main DataMapper class
 *
 * @package    DataMapper
 * @author     Dave Widmer <dave@davewidmer.net> 
 */
class DataMapper
{
	/**
	 * Factory pattern for creating DataMapper instances
	 *
	 * @param  string  The name of the DataMapper to create.
	 */
	public static function factory($name)
	{
		$class = 'DataMapper\\'.$name;
		return new $class;
	}

	/**
	 * Built in class autoloading.
	 *
	 * @see    https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
	 *
	 * @param  string   $class   The name of the class to autoload
	 * @return boolean           Was the class found and loaded?
	 */
	public static function autoload($class)
	{
		$found = false;
		$class = ltrim($class, "\\");

		$base = dirname(__FILE__) . DIRECTORY_SEPARATOR;
		$file = $base . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

		if (is_file($file))
		{
			include $file;
			$found = true;
		}

		return $found;
	}

}
