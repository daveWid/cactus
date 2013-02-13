<?php

namespace Cactus;

/**
 * A base class for all data seeding classes.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class Seed
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
	 * Runs the seed.
	 *
	 * @return boolean  Success
	 */
	public function run()
	{
		return true;
	}

}
