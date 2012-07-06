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
		$data = array($this->primary_key => $key);
		$query = $this->selectQuery()." WHERE ".$this->where($this->primary_key);

		$result = $this->adapter->select($query, $data);

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

		if ($direction !== 'DESC')
		{
			$direction = 'ASC';
		}

		$query = $this->selectQuery()." ORDER BY {$column} {$direction}";
		$result = $this->adapter->select($query);

		return $this->formCollection($result);
	}

	/**
	 * Gets all of the records that satisfy the search parameters.
	 *
	 * @param  array $params   The search parameters
	 * @return \Cactus\Collection
	 */
	public function find(array $params)
	{
		$data = array();
		$where = array();
		foreach ($params as $key => $value)
		{
			$data[$key] = $value;
			$where[] = $this->where($key);
		}

		$query = $this->selectQuery()." WHERE ".join(' AND ', $where);
		$result = $this->adapter->select($query, $data);

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

		$query = "INSERT INTO {$this->table} ".$this->buildInsert($data);
		$result = $this->adapter->insert($query, $data);

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

		$query = "UPDATE {$this->table} SET ".$this->buildUpdate($data);
		$query .= " WHERE ".$this->where($this->primary_key);

		$data[$this->primary_key] = $entity->{$this->primary_key};

		$result = $this->adapter->update($query, $data);

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
		$data = array($this->primary_key => $entity->{$this->primary_key});

		$query = "DELETE FROM {$this->table} WHERE ".$this->where($this->primary_key);

		$affected = $this->adapter->delete($query, $data);
		$success = $affected > 0;

		if ($success)
		{
			$entity = null;
		}

		return $success;
	}

	/**
	 * The start of a select query.
	 *
	 * @return string
	 */
	protected function selectQuery()
	{
		return "SELECT * FROM {$this->table}";
	}

	/**
	 * Creates a simple where clause.
	 *
	 * @param  string $key    The param key
	 * @param  string $op     The operator
	 * @return string
	 */
	private function where($key, $op = "=")
	{
		return "{$key} {$op} :{$key}";
	}

	/**
	 * Builds the end of an insert query.
	 *
	 * @param  array $data  The data to use for building
	 * @return string
	 */
	private function buildInsert(array $data)
	{
		$keys = array_keys($data);

		$sql[] = "(".join(',', $keys).")";
		$sql[] = "VALUES";
		$sql[] = "(";

		$placeholders = array();
		foreach ($keys as $name)
		{
			$placeholders[] = ":{$name}";
		}

		$sql[] = join(',', $placeholders);
		$sql[] = ")";

		return join(" ", $sql);
	}

	/**
	 * Builds the end of an update statement.
	 *
	 * @param  array $data  The data to set
	 * @return string
	 */
	private function buildUpdate(array $data)
	{
		$sql = array();
		foreach (array_keys($data) as $key)
		{
			$sql[] = "{$key} = :{$key}";
		}

		return join(", ", $sql);
	}

}
