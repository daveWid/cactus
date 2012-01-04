<?php
namespace DataMapper\Kohana;

/**
 * The driver for the Kohana framework.
 *
 * This class assumes that you have the Database module activated.
 *
 * @package    DataMapper
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Driver implements \DataMapper\Driver
{
	/**
	 * @var   string   The name of the table
	 */
	protected $table;

	/**
	 * @var   string   The name of the primary key column
	 */
	protected $primary_key;

	/**
	 * @var   array    The list of columns in the table
	 */
	protected $columns = array();

	/**
	 * @var   string   The name of the object to return in operations
	 */
	protected $object_class;

	/**
	 * @var   array   A list of all table relationships 
	 */
	protected $relationships = array();

	/**
	 * Returns a row from the table with the given id for the primary key column
	 *
	 * @param   int   $id   The primary id value
	 * @return  DataMapper\Object
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
	 * Saves an object.
	 *
	 * @param   DataMapper\Object   $object     The object to save
	 * @param   boolean             $validate   Should the data be validated first??
	 * @return  mixed                           DataMapper\Object OR boolean false for failed validation
	 */
	public function save(\Datamapper\Object & $object, $validate = true)
	{
		return ($object->is_new()) ?
			$this->create($object, $validate) :
			$this->update($object, $validate) ;
	}

	/**
	 * Creates a new record in the database.
	 *
	 * @throws  Datamapper_Exception           The passed in object is not the correct type
	 * @param   DataMapper_Object   $object    The object to create
	 * @return  mixed                          array [insert_id, affected rows] OR boolean false for failed validation
	 */
	public function create(\Datamapper\Object & $object, $validate = true)
	{
		// Make sure it is a new object
		if ($object->is_new() !== true)
		{
			throw new \DataMapper\Exception("Use DataMapper::update to save existing objects");
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
	 * @throws  DataMapper\Exception           The passed in object is not the correct type
	 * @param   DataMapper\Object   $object    The object to create
	 * @param   boolean             $validate  Validate the object before saving?
	 * @return  mixed                          (int) affected rows OR boolean false for failed validation
	 */
	public function update(\DataMapper\Object & $object, $validate = true)
	{
		// Make sure it is not a new object
		if ($object->is_new() === true)
		{
			throw new \DataMapper\Exception("Use DataMapper::create to save a new object");
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
	 * @throws  DataMapper\Exception           The passed in object is not the correct type
	 * @param   DataMapper\Objecct   $object   The database object to delete
	 * @return  int                            The number of affected rows 
	 */
	public function delete(\DataMapper\Object & $object)
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
	 * Gets all of the relationships for the DataMapper
	 *
	 * @return  array  List of relationship
	 */
	public function relationships()
	{
		return $this->relationships;
	}

	/**
	 * Cleans a result set before returning it.
	 *
	 * @param   \Database_Result   $result   A result array
	 * @return  DataMapper\Collection
	 */
	public function clean_result(\Database_Result $result)
	{
		$data = array();
		foreach ($result as $row)
		{
			$data[] = $this->add_relationship($row->clean());
		}

		return new \DataMapper\Collection($data);
	}

	/**
	 * Adds a relationship to a result.
	 *
	 * @param   DataMapper\Object   $result   The DataMapper object to add relationships to
	 * @return  DataMapper\Object
	 */
	public function add_relationship(\DataMapper\Object $result)
	{
		// If no relationships, then there is nothing to do
		if (empty($this->relationships))
		{
			return $result;
		}

		// Just a single row
		foreach ($this->relationships as $key => $row)
		{
			$class = "\\DataMapper\\Relationship\\{$row['type']}";
			$result->{$key} = new $class($result->{$row['column']}, $row['mapper'], $row['column']);
		}

		return $result;
	}

	/**
	 * Checks to make sure the object passed in is of the correct type.
	 *
	 * @throws  DataMapper_Exception           The passed in object is not the correct type
	 * @param   DataMapper_Object   $object    The datamapper object to check
	 */
	public function check_object(\DataMapper\Object $object)
	{
		if ( ! $object instanceof $this->object_class)
		{	
			throw new \DataMapper\Exception(get_called_class()." expects a {$this->object_class} object.");
		}
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
			$mapper = new $row['mapper'];
			$mapper->delete_on_column($row['column'], $object->get($row['column']));
		}
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
			if (in_array($key, $this->columns))
			{
				$filtered[$key] = $value;
			}
		}

		return $filtered;
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
