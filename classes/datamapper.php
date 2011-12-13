<?php defined('SYSPATH') or die('No direct script access.');
/**
 * The DataMapper layer for models
 *
 * @package    DataMapper
 * @author     Dave Widmer <dave@davewidmer.net> 
 */
abstract class DataMapper
{
	/**
	 * @var   string   The name of the table
	 */
	protected $_table;

	/**
	 * @var   string   The name of the primary key column
	 */
	protected $_primary_key;

	/**
	 * @var   array    The list of columns in the table
	 */
	protected $_columns = array();

	/**
	 * @var   string   The name of the doa object to return in operations
	 */
	protected $_object_class;

	/**
	 * @var   array   A list of all table relationships 
	 */
	protected $_relationship = array();

	/**
	 * Returns a row from the table with the given id for the primary key column
	 *
	 * @param   int   $id   The primary id value
	 * @return  DataMapper_Object
	 */
	public function get($id = null)
	{
		if ($id === null)
		{
			return new $this->_object_class(true);
		}

		$result = DB::select()
			->from($this->_table)
			->where($this->_primary_key, '=', $id)
			->limit(1)
			->as_object($this->_object_class)
			->execute();

		if (count($result) == 0)
		{
			return null;
		}

		$result = $this->_add_relationships($result->current());
		return $result->clean();
	}

	/**
	 * Gets all of the rows in the database. 
	 *
	 * @param   string   $column      The column to order on
	 * @param   string   $direction   The directory to sort
	 * @return  array (of DataMapper_Objects)
	 */
	public function all($column = null, $direction = 'DESC')
	{
		if ($column === null)
		{
			$column = $this->_primary_key;
		}

		return $this->find(array(
			'order_by' => array($column, $direction)
		));
	}

	/**
	 * Finds records with the given parameters.
	 *
	 * @param   array   $params   The database parameters to search on
	 * @return  array             An array of DataMapper_Object items
	 */
	public function find($params = array())
	{
		$query = DB::select()->from($this->_table)->as_object($this->_object_class);

		// Loop through the params, all keys that aren't in the column list are converted to DB::select method calls.
		foreach ($params as $key => $value)
		{
			if (in_array($key, $this->_columns))
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

		return $this->_clean_result($query->execute());
	}

	/**
	 * Cleans a result set before returning it.
	 *
	 * @param   Database_Result   $result   A result array
	 * @return  DataMapper_Collection
	 */
	protected function _clean_result($result)
	{
		$data = array();
		foreach ($result as $row)
		{
			$data[] = $row->clean();
		}

		return new DataMapper_Collection($data);
	}

	/**
	 * Saves an object.
	 *
	 * @param   DataMapper_Object   $object     The object to save
	 * @param   boolean             $validate   Should the data be validated first??
	 * @return  mixed                           DataMapper_Object OR boolean false for failed validation
	 */
	public function save(Datamapper_Object & $object, $validate = true)
	{
		return ($object->is_new()) ?
			$this->create($object, $validate) :
			$this->update($object, $validate) ;
	}

	/**
	 * Creates a new record in the database.
	 *
	 * @throws  Datamapper_Exception
	 * @param   DataMapper_Object   $object    The object to create
	 * @return  mixed                          array [insert_id, affected rows] OR boolean false for failed validation
	 */
	public function create(Datamapper_Object & $object, $validate = true)
	{
		// Make sure it is a new object
		if ($object->is_new() !== true)
		{
			throw new DataMapper_Exception("User DataMapper::update to save existing objects");
		}

		// Check for validation and run it
		if ($validate AND ! $object->validate())
		{
			return false;
		}

		$data = $this->filter($object->data());

		$result = DB::insert($this->_table)
			->columns(array_keys($data))
			->values(array_values($data))
			->execute();

		$object = $this->get($result[0]);
		return $result;
	}

	/**
	 * Updates a record
	 *
	 * @throws  DataMapper_Exception
	 * @param   DataMapper_Object   $object    The object to create
	 * @param   boolean             $validate  Validate the object before saving?
	 * @return  mixed                          (int) affected rows OR boolean false for failed validation
	 */
	public function update(DataMapper_Object & $object, $validate = true)
	{
		// Make sure it is not a new object
		if ($object->is_new() === true)
		{
			throw new DataMapper_Exception("Use DataMapper::create to save a new object");
		}

		// Check for validation and run it
		if ($validate AND ! $object->validate())
		{
			return false;
		}

		$data = $this->filter($object->modified());
		$affected = 0;

		if ( ! empty($data))
		{
			$affected = DB::update($this->_table)
				->set()
				->where($this->_primary_key, '=', $object->{$this->_primary_key})
				->limit(1)
				->execute();
		}

		$object->clean();
		return $affected;
	}

	/**
	 * Deletes a record from the database
	 *
	 * @throws  DataMapper_Exception
	 * @param   DataMapper_Objecct   $object   The database object to delete
	 * @return  int                            The number of affected rows 
	 */
	public function delete(& $object)
	{
		if ($object === null)
		{
			throw new DataMapper_Exception("Cannot delete null object");
		}

		$affected = DB::delete($this->_table)
			->where($this->_primary_key, '=', $object->{$this->_primary_key})
			->limit(1)
			->execute();

		if ($affected == 1)
		{
			$object = null;
		}

		return $affected;
	}

	/**
	 * Filters any data against the column list to make sure the insert/update functions work properly.
	 *
	 * @param   array   $data   The data to filter
	 * @return  array           Filtered data
	 */
	public function filter(array $data)
	{
		$filtered = array();

		foreach ($data as $key => $value)
		{
			if (in_array($key, $this->_columns))
			{
				$filtered[$key] = $value;
			}
		}

		return $filtered;
	}

	/**
	 * Add in relationships for the result set.
	 *
	 * @param   mixed   $result   A DataMapper_Object or an array of them
	 * @return  mixed
	 */
	protected function _add_relationships($result)
	{
		// If no relationships, then there is nothing to do
		if (empty($this->_relationship))
		{
			return $result;
		}

		if (is_array($result))
		{
			
		}
		else
		{
			// Just a single row
			foreach ($this->_relationship as $key => $row)
			{
				$class = "DataMapper_Relationship_{$row['type']}";
				$result->{$key} = new $class($result->{$this->_primary_key}, $row['mapper'], $row['column']);
			}

			return $result;
		}
	}

}
