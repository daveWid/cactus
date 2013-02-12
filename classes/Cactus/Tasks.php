<?php

namespace Cactus;

/**
 * Utility tasks that can be run.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Tasks
{
	/**
	 * @var string  Absolute path to the tasks folder
	 */
	private $path;

	/**
	 * @var string  The path to the migrations folder
	 */
	private $migration_path;

	/**
	 * @var \Cactus\Adapter  The adapter used for the migrations
	 */
	private $adapter;

	/**
	 * @param string          $path    The path to the tasks folder
	 * @param \Cactus\Adapter $adapter The adapter to connect to the data source
	 */
	public function __construct($path, \Cactus\Adapter $adapter)
	{
		$this->path = rtrim(realpath($path), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		$this->migration_path = $this->path . 'migrations' . DIRECTORY_SEPARATOR;
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
	 * Migrates a database up to a current state.
	 *
	 * @param  string $from    The migration version
	 * @return array           List of debug style messages.
	 */
	public function migrate($from = 0)
	{
		$migrations = $this->findMigrations($from);
		return $this->runMigrations($migrations, 'up');
	}

	/**
	 * Rolls back a migration.
	 *
	 * @param  integer         $to       The version to rollback to
	 * @return array                     List if debug style messages
	 */
	public function rollback($to = 0)
	{
		$migrations = $this->findMigrations($to, true);
		return $this->runMigrations($migrations, 'down');
	}

	/**
	 * Find the given migrations.
	 *
	 * @param  string  $version The version limit
	 * @param  boolean $reverse Reverse the migration numbers?
	 * @return array            List of migration files
	 */
	private function findMigrations($version, $reverse = false)
	{
		/** @link http://www.php.net/manual/en/function.scandir.php */
		$flag = $reverse ? 1 : 0;
		$files = array_diff(scandir($this->migration_path, $flag), array('.', '..', '.DS_Store'));

		$found = array();

		foreach ($files as $file)
		{
			$info = \pathinfo($file);
			list($id, $name) = explode("_", $info['filename']);

			if ($id > $version)
			{
				$info['classname'] = $name."_".$id;
				$info['name'] = \str_replace("Migration", "", $name);
				$info['id'] = $id;

				$found[] = $info;
			}
		}

		return $found;
	}

	/**
	 * Runs the given migrations.
	 *
	 * @param  array  $migrations The list of migrations
	 * @param  string $fn         The migration function to run (up, down)
	 * @return array              An array of output messages
	 */
	private function runMigrations($migrations, $fn)
	{
		$output = array();
		$path = $this->path.'migrations'.DIRECTORY_SEPARATOR;
		$type = $fn === 'up' ? 'Migration' : 'Rollback';

		foreach ($migrations as $info)
		{
			include_once $path.$info['basename'];
			$migration = new $info['classname']($this->adapter);
			$message = $migration->{$fn}() === true ? "Success" : "Failed";
			$output[] = "{$type} #{$info['id']} {$info['name']}: {$message}";
		}

		return $output;
	}

}
