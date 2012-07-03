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
	public $dataSource;

	/**
	 * @var string  The object class to return rows as
	 */
	public $objectClass = "\Cactus\Entity";

	/**
	 * Attempt to get a row of data by using the passed in key.
	 *
	 * @param  mixed $key  The key that is used to search for a row
	 * @return \Cactus\Entity
	 */
	abstract public function fetchOne($key);

	/**
	 * Get all of the records in the data set.
	 *
	 * @return \Cactus\ResultSet
	 */
	abstract public function fetchAll();

	/**
	 * Gets all of the records that satisfy the search parameters.
	 *
	 * @param  array $params   The search parameters
	 * @return \Cactus\ResultSet
	 */
	abstract public function find(array $params = array());

	/**
	 * Saves an entity.
	 *
	 * @param \Cactus\Entity $entity  The entity to save
	 */
	abstract public function save(\Cactus\Entity & $entity);

	/**
	 * Deletes an entity.
	 *
	 * @param  \Cactus\Entity $entity  The entity to delete
	 * @return boolean                 Was the delete successful?
	 */
	abstract public function delete(\Cactus\Entity & $entity);

}
