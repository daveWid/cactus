<?php

namespace Cactus;

/**
 * An abstract implentation for the Driver interface.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class Driver implements \Cactus\DriverInterface
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
	 * @var   array  The eager loaded data.
	 */
	private $eager = null;

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
	 * Gets all of the relationships for the DataMapper
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

		$process = function($row) use ($self, $eager){
			$row = $self->add_relationship($row, $eager);
			return $row->clean();
		};

		return new \Cactus\Collection(array_map($process, $result));
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
			if (in_array($key, $this->columns))
			{
				$filtered[$key] = $value;
			}
		}

		return $filtered;
	}

}
