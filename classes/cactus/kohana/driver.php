<?php

namespace Cactus\Kohana;

/**
 * The driver for the Kohana framework.
 *
 * This class assumes that you have the Database module activated.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Driver extends \Cactus\Driver
{
	/**
	 * Returns a row from the table with the given id for the primary key column
	 *
	 * @param   int   $id   The primary id value
	 * @return  Cactus\Entity
	 */
	public function get($id)
	{
		$result = $this->default_query()
			->where($this->primary_key, '=', $id)
			->limit(1)
			->execute();

		if (count($result) == 0)
		{
			return null;
		}

		$result = $this->add_relationship($result->current());
		return $result->clean();
	}

	/**
	 * Gets all of the rows in the database. 
	 *
	 * @param   string   $column      The column to order on
	 * @param   string   $direction   The directory to sort
	 * @return  array                 An array of DataMapper\Object items
	 */
	public function all($column = null, $direction = 'DESC')
	{
		if ($column === null)
		{
			$column = $this->primary_key;
		}

		return $this->find(array(
			'order_by' => array($column, $direction)
		));
	}

	/**
	 * Finds records with the given parameters.
	 *
	 * @param   array   $params   The database parameters to search on
	 * @return  array             An array of DataMapper\Object items
	 */
	public function find($params = array())
	{
		$query = $this->default_query();

		// Loop through the params, all keys that aren't in the column list are converted to DB::select method calls.
		foreach ($params as $key => $value)
		{
			if (in_array($key, $this->columns))
			{
				if ( ! is_array($value))
				{
					$value = array($value, "=");
				}

				list($value, $op) = $value;
				$query->where($key, $op, $value);
			}
			else
			{
				if ( ! is_array($value))
				{
					$value = array($value);
				}

				call_user_func_array(array($query, $key), $value);
			}
		}

		return $this->clean_result($query->execute());
	}

	/**
	 * Creates a new record in the database.
	 *
	 * @throws  \Cactus\Exception           The passed in object is not the correct type
	 * @param   \Cactus\ENtity   $object    The object to create
	 * @return  mixed                       array [insert_id, affected rows] OR boolean false for failed validation
	 */
	public function create(\Cactus\Entity & $object, $validate = true)
	{
		// Make sure it is a new object
		if ($object->is_new() !== true)
		{
			throw new \Cactus\Exception("Use \Cactus\Driver::update to save existing objects");
		}

		$this->check_object($object);

		// Check for validation and run it
		if ($validate AND ! $object->validate())
		{
			return false;
		}

		$data = $this->filter($object->data());

		$result = \DB::insert($this->table)
			->columns(array_keys($data))
			->values(array_values($data))
			->execute();

		$object = $this->get($result[0]);
		return $result;
	}

	/**
	 * Updates a record
	 *
	 * @throws  \Cactus\Exception           The passed in object is not the correct type
	 * @param   \Cactus\Entity   $object    The object to create
	 * @param   boolean          $validate  Validate the object before saving?
	 * @return  mixed                       (int) affected rows OR boolean false for failed validation
	 */
	public function update(\Cactus\Entity & $object, $validate = true)
	{
		// Make sure it is not a new object
		if ($object->is_new() === true)
		{
			throw new \Cactus\Exception("Use \Cactus\Driver::create to save a new object");
		}

		$this->check_object($object);

		// Check for validation and run it
		if ($validate AND ! $object->validate())
		{
			return false;
		}

		$data = $this->filter($object->modified());
		$affected = 0;

		if ( ! empty($data))
		{
			$affected = \DB::update($this->table)
				->set($data)
				->where($this->primary_key, '=', $object->get($this->primary_key))
				->limit(1)
				->execute();
		}

		$object->clean();
		return $affected;
	}

	/**
	 * Deletes a record from the database
	 *
	 * @throws  \Cactus\Exception          The passed in object is not the correct type
	 * @param   \Cactus\Entity   $object   The database object to delete
	 * @return  int                        The number of affected rows 
	 */
	public function delete(\Cactus\Entity & $object)
	{
		$this->check_object($object);

		$affected = \DB::delete($this->table)
			->where($this->primary_key, '=', $object->get($this->primary_key))
			->limit(1)
			->execute();

		if ($affected == 1)
		{
			$object = null;
		}

		return $affected;
	}

	/**
	 * Cleans a result set before returning it.
	 *
	 * @param   Database_Result   $result   An iteratable object
	 * @return  \Cactus\Collection
	 */
	public function clean_result($result)
	{
		return parent::clean_result($result->as_array());
	}

	/**
	 * Deletes all of the rows in a table where the column equals the value
	 *
	 * @param   string   $column    The column
	 * @param   string   $value     The column value
	 * @param   string   $op        The operator to use
	 * @return  int                 The number of affected rows
	 */
	public function delete_on_column($column, $value, $op = "=")
	{
		return \DB::delete($this->table)
			->where($column, $op, $value)
			->execute();
	}

	/**
	 * Runs a cascade delete.
	 */
	protected function cascade($object)
	{
		if(empty($this->relationships))
		{
			return; // Nothing to do....
		}

		foreach ($this->relationships as $row)
		{
			$mapper = new $row['driver'];
			$mapper->delete_on_column($row['column'], $object->get($row['column']));
		}
	}

	/**
	 * Gets the default query for selecting rows.
	 *
	 * @return   Database_Query_Builder_Select
	 */
	protected function default_query()
	{
		return \DB::select()->from($this->table)->as_object($this->object_class);
	}

}
