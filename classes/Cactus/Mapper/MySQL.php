<?php

namespace Cactus\Mapper;

/**
 * A Mapper that uses MySQL. Cool enne?
 *
 * This class has no SQL Injection protection so please sanitize the data before
 * you send data to the database.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class MySQL extends \Cactus\Mapper
{
	/**
	 * Attempt to get a row of data by using the passed in key.
	 *
	 * @param  mixed $key  The value of the primary key that is used to find the data
	 * @return \Cactus\Entity or null
	 */
	public function get($key)
	{
		$query = "SELECT * FROM {$this->table} WHERE {$this->primary_key} = {$key}";
		$result = $this->adapter->select($query);

		if (empty($result))
		{
			return null;
		}

		$collection = $this->formCollection($result);
		return $collection->current();
	}

	/**
	 * Get all of the records in the data set.
	 *
	 * @param  string $column     The column to sort on
	 * @param  string $direction  The direction to sort on (ASC or DESC)
	 * @return \Cactus\Collection
	 */
	public function all($column = null, $direction = 'DESC')
	{
		if ($column === null)
		{
			$column = $this->primary_key;
		}

		$query = "SELECT * FROM {$this->table} ORDER BY {$column} {$direction}";
		$result = $this->adapter->select($query);

		return $this->formCollection($result);
	}

	/**
	 * Gets all of the records that satisfy the search parameters.
	 *
	 * @param  array $params   The search parameters
	 * @return \Cactus\Collection
	 */
	public function find(array $params = array())
	{
		$set = array();
		foreach ($params as $key => $value)
		{
			$set[] = "{$key} = '{$value}'";
		}

		$query = "SELECT * FROM {$this->table} WHERE ".join(',', $set);
		$result = $this->adapter->select($query);

		return $this->formCollection($result);
	}

	/**
	 * Saves an entity.
	 *
	 * @param \Cactus\Entity $entity  The entity to save
	 * @return mixed                  create = array($id, $affected) | update = $affected
	 */
	public function save(\Cactus\Entity & $entity)
	{
		return ($entity->isNew()) ?
			$this->create($entity) :
			$this->update($entity) ;
	}

	/**
	 * Creates a new row.
	 *
	 * @param  \Cactus\Entity $entity  The entity to create
	 * @return array                   array($id, $affected)
	 */
	protected function create(\Cactus\Entity & $entity)
	{
		$data = $this->revert($entity->getModifiedData());
		$data = $this->filter($data);

		$columns = join(',', array_keys($data));
		$values = array_map(array($this->adapter, "quote"), array_values($data));

		$query = "INSERT INTO {$this->table} ({$columns}) VALUES (".join(",", $values).")";
		$result = $this->adapter->insert($query);

		$entity = $this->get($result[0]);

		return $result;
	}

	/**
	 * Updates an existing entity.
	 *
	 * @param  \Cactus\Entity $entity  The entity to update
	 * @return int                     Affected rows
	 */
	protected function update(\Cactus\Entity & $entity)
	{
		$data = $this->revert($entity->getModifiedData());
		$data = $this->filter($data);

		$set = array();
		foreach ($data as $key => $value)
		{
			$set[] = "{$key} = ".$this->adapter->quote($value);
		}

		$pk = $this->adapter->quote($entity->{$this->primary_key});
		$query = "UPDATE {$this->table} SET ".join(',', $set)." WHERE {$this->primary_key} = {$pk}";
		$result = $this->adapter->update($query);

		$entity->reset();
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
		$value = $entity->{$this->primary_key};
		$query = "DELETE FROM {$this->table} WHERE {$this->primary_key} = '{$value}'";

		$affected = $this->adapter->delete($query);
		$success = $affected > 0;

		if ($success)
		{
			$entity = null;
		}

		return $success;
	}

}
