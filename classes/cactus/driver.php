<?php
namespace DataMapper;

/**
 * The driver for the Kohana framework.
 *
 * This class assumes that you have the Database module activated.
 *
 * @package    DataMapper
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class Driver implements \DataMapper\DriverInterface
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
			'ORDER BY' => $column." ".$direction
		));
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
	 * @param   mixed   $result   An iteratable object
	 * @return  DataMapper\Collection
	 */
	public function clean_result($result)
	{
		$self = $this;
		$callback = function($row) use ($self) {
			$row = $self->add_relationship($row);
			return $row->clean();
		};

		$data = array_map($callback, $result);

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

}
