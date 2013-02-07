<?php

namespace Cactus;

/**
 * The entity class is a representation of data from a data source as an object.
 *
 * @package   Cactus
 * @author    Dave Widmer <dave@davewidmer.net>
 */
class Entity
{
	/**
	 * @var \Cactus\Converter  The class used to convert strings into native php types
	 */
	public static $converter = null;

	/**
	 * @var \Cactus\Reverter  The class used to revert data back into strings.
	 */
	public static $reverter = null;

	/**
	 * @var array  The data structure for all internal data
	 */
	public $dataStructure = array();

	/**
	 * @var boolean  Is this a new object?
	 */
	private $is_new = false;

	/**
	 * @var array  The internal data storage array
	 */
	private $data = array();

	/**
	 * @var array  A list of properties that have been modified.
	 */
	private $modified = array();

	/**
	 * If data is passed into the entity it will be marked as new, otherwise it
	 * is an existing "row" of data.
	 *
	 * @param array $data       Initial data for a new entity.
	 * @param array $structure  The data structure of the entity properties
	 */
	public function __construct(array $data = null, array $structure = null)
	{
		if ($structure !== null)
		{
			$this->dataStructure = $structure;
		}

		if (self::$converter === null)
		{
			self::$converter = new \Cactus\Converter;
		}

		if (self::$reverter === null)
		{
			self::$reverter = new \Cactus\Reverter;
		}

		if ($data !== null)
		{
			$this->is_new = true;
			$this->fill($data);
		}
	}

	/**
	 * Is this a new Entity?
	 *
	 * @return boolean
	 */
	public function isNew()
	{
		return $this->is_new;
	}

	/**
	 * Magic "getter".
	 *
	 * @param string $name  The property name to get.
	 */
	public function __get($name)
	{
		return $this->data[$name];
	}

	/**
	 * Gets the data as an array.
	 *
	 * @param  array $keys  A list of keys to pull out in case all of the data isn't needed.
	 * @return array 
	 */
	public function asArray(array $keys = null)
	{
		if ($keys === null)
		{
			return $this->data;
		}

		$data = array();
		foreach ($keys as $key)
		{
			$data[$key] = $this->data[$key];
		}

		return $data;
	}

	/**
	 * Magic "setter"
	 *
	 * @param string $name  The property name
	 * @param mixed  $value The property value
	 */
	public function __set($name, $value)
	{
		if ( ! isset($this->data[$name]) OR $this->data[$name] !== $value)
		{
			$this->data[$name] = $this->convert($name, $value);

			if ( ! in_array($name, $this->modified))
			{
				$this->modified[] = $name;
			}
		}
	}

	/**
	 * Sets an array of properties.
	 *
	 * @param array $data  The data to set
	 */
	public function fill(array $data)
	{
		foreach ($data as $key => $value)
		{
			$this->{$key} = $value;
		}
	}

	/**
	 * Gets an array of all of the internal data that have been modified.
	 *
	 * @return array
	 */
	public function getModifiedData()
	{
		$modified = array();

		foreach ($this->modified as $key)
		{
			$modified[$key] = $this->data[$key];
		}

		return $modified;
	}

	/**
	 * Converts strings over to native php data types.
	 *
	 * @param  string $name   The name of the property
	 * @param  mixed  $value  The current value
	 * @return mixed          The converted value
	 */
	private function convert($name, $value)
	{
		$method = $this->getDataType($name);
		if ($method !== false)
		{
			$value = self::$converter->$method($value);
		}

		return $value;
	}

	/**
	 * Reverts all native php types back to their string values.
	 *
	 * @param  array  $data  The data to revert. Uses internal data if nothing is passed in.
	 * @return array
	 */
	public function revert(array $data = array())
	{
		$reverted = array();

		if (empty($data))
		{
			$data = $this->data;
		}

		foreach ($data as $key => $value)
		{
			$method = $this->getDataType($key);
			if ($method !== false && $value !== null)
			{
				$value = self::$reverter->$method($value);
			}

			$reverted[$key] = $value;
		}

		return $reverted;
	}

	/**
	 * Resets all of the modified data.
	 *
	 * @return \Cactus\Entity
	 */
	public function reset()
	{
		$this->modified = array();
		return $this;
	}

	/**
	 * Gets the data type from the data structure.
	 *
	 * @param  string $name The property name to get the type of
	 * @return mixed        A string data type or boolean false
	 */
	private function getDataType($name)
	{
		$type = false;
		if (array_key_exists($name, $this->dataStructure) AND $this->dataStructure[$name] !== false)
		{
			$type = $this->dataStructure[$name];
		}

		return $type;
	}

}
