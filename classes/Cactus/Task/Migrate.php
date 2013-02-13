<?php

namespace Cactus\Task;

/**
 * Database Migration.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Migrate extends \Cactus\Task
{
	/**
	 * Migrates a database up to a current state.
	 *
	 * @param  string $from    The migration version
	 * @return array           List of debug style messages.
	 */
	public function migrate($from = 0)
	{
		$files = $this->getFiles();
		$migrations = $this->findMigrations($from, $files);
		return $this->runMigrations($migrations, 'up');
	}

	/**
	 * Rolls back a migration.
	 *
	 * @param  string $to  The version to rollback to
	 * @return array       List if debug style messages
	 */
	public function rollback($to = 0)
	{
		$files = $this->getFiles(true);
		$migrations = $this->findMigrations($to, $files);
		return $this->runMigrations($migrations, 'down');
	}

	/**
	 * Find the given migrations.
	 *
	 * @param  string  $version The version limit
	 * @param  array   $files   The files to search through
	 * @return array            List of migration files
	 */
	private function findMigrations($version, $files)
	{
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

		foreach ($migrations as $info)
		{
			include_once $this->path.$info['basename'];
			$migration = new $info['classname']($this->adapter);
			$output[] = array(
				'name' => $info['name'],
				'id' => $info['id'],
				'success' => $migration->{$fn}()
			);
		}

		return $output;
	}

}
