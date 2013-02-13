<?php

namespace Cactus\Task;

/**
 * Seed a database with some default data.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Seed extends \Cactus\Task
{
	/**
	 * Runs a single seed class.
	 *
	 * @throws \Cactus\Exception
	 *
	 * @param  string $name The name of the seed class to run
	 * @return boolean      Success
	 */
	public function single($name)
	{
		$file = $this->path.$name.".php";
		if ( ! \file_exists($file))
		{
			throw new \Cactus\Exception("Seed class {$name} does not exist");
		}

		include_once $file;
		$seed = new $name($this->adapter);

		return $seed->run();
	}

	/**
	 * Run the given list of seed classes.
	 *
	 * @param  array  $list The list of classes
	 * @return array        [[$name => (boolean) success]]
	 */
	public function multiple(array $list)
	{
		$output = array();
		foreach ($list as $name)
		{
			$output[$name] = $this->single($name);
		}

		return $output;
	}

	/**
	 * Runs all of the seed classes.
	 *
	 * @return array  [[$name => (boolean) success]]
	 */
	public function all()
	{
		$files = array();
		foreach($this->getFiles() as $file)
		{
			$files[] = substr($file, 0, -4);
		}

		return $this->multiple($files);
	}

}
