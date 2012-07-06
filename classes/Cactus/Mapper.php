<?php

namespace Cactus;

/**
 * The mapper class is the gateway for defining data structures as well as knowing
 * how to get them.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class Mapper
{
	/**
	 * @var \Cactus\Adapter  The adapter used to fetch data
	 */
	public $adapter;

	/**
	 * @var string  The object class to return rows as
	 */
	public $objectClass = "\Cactus\Entity";

	/**
	 * @var string  The database table
	 */
	protected $table;

	/**
	 * @var string  The name of the primary key column
	 */
	protected $primary_key;

	/**
	 * @var array   A listing of columns in the data
	 */
	protected $columns = array();

	/**
	 * @var array  A list of validation rules.
	 */
	protected $rules = array();

	/**
	 * Creates a new mapper. You can optionally pass in the data source adapter.
	 *
	 * @param \Cactus\Adapter $adapter  The data adapter.
	 */
	public function __construct(\Cactus\Adapter $adapter = null)
	{
		if ($adapter !== null)
		{
			$this->adapter = $adapter;
		}

		$this->init();
	}

	/**
	 * An initilization function so we don't have to override the constructor.
	 */
	protected function init(){}

	/**
	 * Gets the validation rules.
	 *
	 * @return array
	 */
	public function getValidationRules()
	{
		return $this->rules;
	}

	/**
	 * Gets the column list of the mapper.
	 *
	 * @return array
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 * Takes the result array and converts it and makes a collection out of it.
	 *
	 * @param  array $result  An array of results
	 * @return \Cactus\Collection
	 */
	protected function formCollection(array $result)
	{
		$collection = new \Cactus\Collection;

		foreach ($result as $row)
		{
			$row = $this->convert($row);
			$row->reset();
			$collection->add($row);
		}

		return $collection;
	}

	/**
	 * Converts an array over to the object class.
	 *
	 * @param  array $object  The array to convert
	 * @return mixed          The object class specified in the mapper with native php data types
	 */
	public function convert($object)
	{
		$converted = new $this->objectClass;

		foreach ($object as $key => $value)
		{
			if (array_key_exists($key, $this->columns) AND $this->columns[$key] !== false)
			{
				$method = $this->columns[$key];
				$value = \Cactus\Converter::$method($value);
			}

			$converted->{$key} = $value;
		}

		return $converted;
	}

	/**
	 * Reverts all native php types back to their string values.
	 *
	 * @param  array $data The data to revert
	 * @return array
	 */
	public function revert(array $data)
	{
		$reverted = array();

		foreach ($data as $key => $value)
		{
			if (array_key_exists($key, $this->columns) AND $this->columns[$key] !== false)
			{
				$method = $this->columns[$key];
				$value = \Cactus\Reverter::$method($value);
			}

			$reverted[$key] = $value;
		}

		return $reverted;
	}

	/**
	 * Filters the data to make sure only fields that are specified in the mapper
	 * get set in the query.
	 *
	 * @param  array $data  The data to filter.
	 * @return array
	 */
	public function filter(array $data)
	{
		$filtered = array();

		foreach ($data as $key => $value)
		{
			if (array_key_exists($key, $this->columns))
			{
				$filtered[$key] = $value;
			}
		}

		return $filtered;
	}

	/**
	 * Attempt to get a row of data by using the passed in key.
	 *
	 * @param  mixed $key  The value of the primary key that is used to find the data
	 * @return \Cactus\Entity
	 */
	abstract public function get($key);

	/**
	 * Get all of the records in the data set.
	 *
	 * @param  string $column     The column to sort on
	 * @param  string $direction  The direction to sort on (ASC or DESC)
	 * @return \Cactus\Collection
	 */
	abstract public function all($column = null, $direction = 'DESC');

	/**
	 * Gets all of the records that satisfy the search parameters.
	 *
	 * @param  array $params   The search parameters
	 * @return \Cactus\Collection
	 */
	abstract public function find(array $params);

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
