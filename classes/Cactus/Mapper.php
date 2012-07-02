<?php

namespace Cactus;

/**
 * The mapper class is the starting point to fetch data from a datasource.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class Mapper
{
	/**
	 * @var \Cactus\DataSource  The data source used to get the data
	 */
	private static $dataSource;

	/**
	 * Fetches the current data source
	 *
	 * @return \Cactus\DataSource
	 */
	public static function getDataSource()
	{
		return self::$dataSource;
	}

	/**
	 * Sets the data sourced used to get results.
	 *
	 * @param \Cactus\DataSource $source  The data source to use with the mapper
	 */
	public function setDataSource(\Cactus\DataSource $source)
	{
		self::$dataSource = $source;
	}

}
