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
	 * @param array $data  Initial data for a new entity.
	 */
	public function __construct(array $data = null)
	{
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
			$this->data[$name] = $value;

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
	 * Resets all of the modified data.
	 *
	 * @return \Cactus\Entity
	 */
	public function reset()
	{
		$this->modified = array();
		return $this;
	}

}
