<?php

namespace Cactus\Driver;

/**
 * A base driver that you will extend in your projects.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class Base implements \Cactus\Driver
{
	/**
	 * @var \Cactus\Adapter  The database adapter to use when querying the database
	 */
	public static $adapter = null;

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
	 * @var  \Cactus\Field  The field type converter.
	 */
	private $field = null;

	/**
	 * @var   array  The eager loaded data.
	 */
	private $eager = null;

	/**
	 * Creates a new Driver instance.
	 */
	public function __construct()
	{
		$this->field = new \Cactus\Field;
	}

	/**
	 * Returns a row from the table with the given id for the primary key column
	 *
	 * @param   int   $id   The primary id value
	 * @return  Cactus\Entity
	 */
	public function get($id)
	{
		$query = new \Peyote\Select($this->table);
		$query->columns("*")
			->where($this->primary_key, "=", $id)
			->limit(1)
			->compile();

		$result = static::$adapter->select($query, $this->object_class);

		if (count($result) == 0)
		{
			return null;
		}

		$result = $this->process_result($result);
		return $result->current();
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
		$query = new \Peyote\Select($this->table);

		// Loop through the params, all keys that aren't in the column list
		// are converted to \Peyote\Select method calls.
		foreach ($params as $key => $value)
		{
			if (in_array($key, array_keys($this->columns)))
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

		$result = static::$adapter->select($query->compile(), $this->object_class);
		return $this->process_result($result);
	}

	/**
	 * Saves an object.
	 *
	 * @param   \Cactus\Entity   $object     The object to save
	 * @param   boolean          $validate   Should the data be validated first??
	 * @return  mixed                        \Cactus\Entity OR boolean false for failed validation
	 */
	public function save(\Cactus\Entity & $object, $validate = true)
	{
		return ($object->is_new()) ?
			$this->create($object, $validate) :
			$this->update($object, $validate) ;
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

		$insert = new \Peyote\Insert($this->table);
		$insert->columns(array_keys($data))
			->values(array_values($data));

		$result = static::$adapter->insert($insert->compile());

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
			$update = new \Peyote\Update($this->table);
			$update->set($data)
				->where($this->primary_key, '=', $object->get($this->primary_key))
				->limit(1);

			$affected = static::$adapter->update($update->compile());
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

		$delete = new \Peyote\Delete($this->table);
		$delete->where($this->primary_key, '=', $object->get($this->primary_key))
			->limit(1);

		$affected = static::$adapter->delete($delete->compile());

		if ($affected == 1)
		{
			$object = null;
		}

		return $affected;
	}

	/**
	 * Gets all of the records associated in the table joining a table with an IN statement.
	 *
	 * @param  array  $values  The values to join in
	 * @param  string $table   The table to join
	 * @param  string $column  The column to join on
	 * @return array
	 */
	public function join_in(array $values, $table, $column)
	{
		$select = new \Peyote\Select($this->table);
		$select->columns("{$this->table}.*")
			->join($table, "LEFT")->on("{$table}.{$column}", "=", "{$this->table}.{$column}")
			->where("{$table}.{$column}", "IN", $values);

		$result = static::$adapter->select($select->compile(), $this->object_class);
		return $this->process_result($result);
	}

	/**
	 * Gets all of the relationships for this class
	 *
	 * @return  array  List of relationship
	 */
	public function relationships()
	{
		return $this->relationships;
	}

	/**
	 * Processes a result set before returning it.
	 *
	 * @param   mixed   $result   An iteratable object
	 * @return  \Cactus\Collection
	 */
	public function process_result($result)
	{
		$eager = $this->get_eager_data($result);
		$self = $this;

		$processed = array();
		foreach ($result as $row)
		{
			// Convert all of the data over to the native php type
			foreach ($row->data() as $key => $value)
			{
				$row->{$key} = $this->field->convert($this->columns[$key], $value);
			}

			$row = $self->add_relationship($row, $eager);

			$processed[] = $row->clean();
		}

		return new \Cactus\Collection($processed);
	}

	/**
	 * Adds a relationship to a result.
	 *
	 * @param  \Cactus\Entity $entity  The Cactus object to add relationships to
	 * @param  array          $data    If an eager load, the eager load data.
	 * @return \Cactus\Entity
	 */
	public function add_relationship(\Cactus\Entity $result, array $data = array())
	{
		// If no relationships, then there is nothing to do
		if (empty($this->relationships))
		{
			return $result;
		}

		// Just a single row
		foreach ($this->relationships as $name => $config)
		{
			$value = $result->{$config['column']};
			$relationship = \Cactus\Relationship::factory($config, $value);

			// Check for eager loading
			if (isset($data[$name]))
			{
				$value = isset($data[$name][$value]) ? $data[$name][$value] : array();

				if ($config['type'] === \Cactus\Relationship::HAS_ONE)
				{
					$value = isset($value[0]) ? $value[0] : null;
				}

				$relationship->set_result($value);
			}

			$result->{$name} = $relationship;
		}

		return $result;
	}

	/**
	 * Checks to make sure the object passed in is of the correct type.
	 *
	 * @throws  \Cactus\Exception           The passed in object is not the correct type
	 * @param   \Cactus\Entity   $object    The \Cactus\Entity to check
	 */
	public function check_object(\Cactus\Entity $object)
	{
		if ( ! $object instanceof $this->object_class)
		{
			throw new \Cactus\Exception(get_called_class()." expects a {$this->object_class} object.");
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
			if (in_array($key, array_keys($this->columns)))
			{
				$filtered[$key] = $value;
			}
		}

		return $filtered;
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
		$delete = new \Peyote\Delete($this->table);
		$delete->where($column, $op, $value);

		return static::$adapter->delete($delete->compile());
	}

	/**
	 * Runs a cascade delete.
	 */
	protected function cascade($object)
	{
		if(empty($this->relationships) === true)
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
	 * Gets query data from eagerly loaded relationships
	 *
	 * @param  mixed $result An iterable result set
	 * @return array  The eagerly loaded associative array
	 */
	private function get_eager_data($result)
	{
		if ($this->eager !== null)
		{
			return $this->eager;
		}

		$eager = array();

		if ( ! empty($this->relationships))
		{
			foreach ($this->relationships as $name => $config)
			{
				if (isset($config['loading']) AND $config['loading'] === \Cactus\Loading::EAGER)
				{
					$primary = $this->primary_key;
					$data = array_map(function($row) use ($primary){
						return $row->$primary;
					}, $result);

					$driver = new $config['driver'];

					$tmp = array();
					foreach ($driver->join_in($data, $this->table, $this->primary_key) as $row)
					{
						$tmp[$row->$primary][] = $row;
					}

					$eager[$name] = $tmp;
				}
			}
		}

		$this->eager = $eager;
		return $this->eager;
	}

}
