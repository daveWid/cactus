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
	private $dataSource;

	/**
	 * Fetches the current data source
	 *
	 * @return \Cactus\DataSource
	 */
	public function getDataSource()
	{
		return $this->dataSource;
	}

	/**
	 * Sets the data sourced used to get results.
	 *
	 * @param \Cactus\DataSource $source  The data source to use with the mapper
	 */
	public function setDataSource(\Cactus\DataSource $source)
	{
		$this->dataSource = $source;
	}

	/**
	 * Attempt to get a row of data by using the passed in key.
	 *
	 * @param  mixed $key  The key that is used to search for a row
	 * @return \Cactus\Entity
	 */
	abstract function fetchOne($key);

	/**
	 * Get all of the records in the data set.
	 *
	 * @return \Cactus\ResultSet
	 */
	abstract function fetchAll();

	/**
	 * Gets all of the records that satisfy the search parameters.
	 *
	 * @param  array $params   The search parameters
	 * @return \Cactus\ResultSet
	 */
	abstract function find(array $params = array());

	/**
	 * Saves an entity.
	 *
	 * @param \Cactus\Entity $entity  The entity to save
	 */
	abstract function save(\Cactus\Entity & $entity);

	/**
	 * Deletes an entity.
	 *
	 * @param  \Cactus\Entity $entity  The entity to delete
	 * @return boolean                 Was the delete successful?
	 */
	abstract function delete(\Cactus\Entity & $entity);

}
