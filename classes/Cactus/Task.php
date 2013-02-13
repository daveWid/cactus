<?php

namespace Cactus;

/**
 * An abstract class to use as the base for all tasks.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class Task
{
	/**
	 * @var string  Absolute path to the tasks folder
	 */
	protected $path;

	/**
	 * @var \Cactus\Adapter  The adapter used for the migrations
	 */
	protected $adapter;

	/**
	 * @param string          $path    The path to the tasks folder
	 * @param \Cactus\Adapter $adapter The adapter to connect to the data source
	 */
	public function __construct($path, \Cactus\Adapter $adapter)
	{
		$this->path = rtrim(realpath($path), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		$this->adapter = $adapter;
	}

	/**
	 * @return string  The absolute path to the tasks directory.
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @return \Cactus\Adapter  The adapter used to connect to the data source.
	 */
	public function getAdapter()
	{
		return $this->adapter;
	}

	/**
	 * Return all of the files in the path directory.
	 *
	 * @param  boolean $reverse  Find in reverse order?
	 * @return array             File list
	 */
	protected function getFiles($reverse = false)
	{
		/** @link http://www.php.net/manual/en/function.scandir.php */
		$flag = $reverse ? 1 : 0;
		return array_diff(scandir($this->path, $flag), array('.', '..', '.DS_Store'));
	}

}
