<?php

namespace Cactus;

/**
 * Database Migration class.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class Migration
{
	/**
	 * @var \Cactus\Adapter
	 */
	protected $adapter;

	public function __construct(\Cactus\Adapter $adapter)
	{
		$this->adapter = $adapter;
	}

	/**
	 * Runs a migration.
	 *
	 * @return boolean  Success
	 */
	public function up()
	{
		return true;
	}

	/**
	 * Takes a migration down (reverses it)
	 *
	 * @return boolean  Success
	 */
	public function down()
	{
		return true;
	}

}
