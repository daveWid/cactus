<?php

namespace Cactus\Mapper;

/**
 * The MySQL Mapper runs MySQL queries on top of a PDO DataSource.
 *
 * This class does no data sanitation before a query is run, so it is up to you
 * to make sure that you aren't exposing your database to SQL Injection.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class MySQL extends \Cactus\Mapper
{
	/**
	 * @var string  The database table to query on
	 */
	public $table;

	/**
	 * @param string  The primary key used in the table
	 */
	public $primary_key;

	/**
	 * Attempt to get a row of data by using the passed in key.
	 *
	 * @param  mixed $key  The key that is used to search for a row
	 * @return \Cactus\Entity
	 */
	public function fetchOne($key)
	{
		$query = "SELECT * FROM {$this->table} WHERE {$this->primary_key} = :id";
		$result = $this->dataSource->select($query, array('id', $key), $this->objectClass);

		return $result->current();
	}

	/**
	 * Get all of the records in the data set.
	 *
	 * @return \Cactus\ResultSet
	 */
	public function fetchAll()
	{
		$query = "SELECT * FROM {$this->table} ORDER BY {$this->primary_key} DESC";
		return $this->dataSource->select($query, array(), $this->objectClass);
	}

	/**
	 * Gets all of the records that satisfy the search parameters.
	 *
	 * @param  array $params   The search parameters
	 * @return \Cactus\ResultSet
	 */
	public function find(array $params = array())
	{
		$placeholders = array();
		foreach ($params as $column => $value)
		{
			$placeholders[] = "`$column` = :{$column}";
		}

		$query = "SELECT * FROM {$this->table}".join(" AND ", $placeholders);
		return $this->dataSource->select($query, $params, $this->objectClass);
	}

	/**
	 * Saves an entity.
	 *
	 * @param  \Cactus\Entity $entity  The entity to save
	 * @return mixed  If the entity is new this will return array($insert_id, $affected_rows)
	 *                otherwise it return the number of updated rows
	 */
	public function save(\Cactus\Entity & $entity)
	{
		if ($entity->isNew())
		{
			return $this->create($entity);
		}
		else
		{
			return $this->update($entity);
		}
	}

	/**
	 * Creates a new row based on the entity.
	 *
	 * @param  \Cactus\Entity $entity  The entity to save
	 * @return array  array($insert_id, $affected_rows)
	 */
	private function create(\Cactus\Entity & $entity)
	{
		$data = $entity->getModifiedData();
		$columns = array();
		$values = array();

		foreach ($data as $key => $value)
		{
			$columns[] = $key;
			$values[] = ":{$key}";
		}

		$columns = join(",", $values);
		$values = join(",", $values);

		$query = "INSERT INTO {$this->table} ({$columns}) VALUES ($values)";
		$result = $this->dataSource->insert($query, $data);

		$entity = $this->fetchOne($result[0]);
		return $result;
	}

	/**
	 * Updates an existing row in the database.
	 *
	 * @param  \Cactus\Entity $entity  The entity to update
	 * @return int  The number of affected rows
	 */
	private function update(\Cactus\Entity & $entity)
	{
		$set = array();
		foreach ($entity->getModifiedData() as $key => $value)
		{
			$set[] = "{$key} = :{$key}";
		}

		$query = "UPDATE {$this->table} SET {$set} WHERE {$this->primary_key} = {$entity->{$this->primary_key}}";
		$result = $this->dataSource->update($query, $data);

		$entity->clean();
		return $result;
	}

	/**
	 * Deletes an entity.
	 *
	 * @param  \Cactus\Entity $entity  The entity to delete
	 * @return boolean                 Was the delete successful?
	 */
	public function delete(\Cactus\Entity & $entity)
	{
		$query = "DELETE FROM {$this->table} WHERE {$this->primary_key} = {$entity->{$this->primary_key}}";
		$result = $this->dataSource->delete($query);

		if ($result === 0)
		{
			return false;
		}

		$result = null;
		return true;
	}

}
