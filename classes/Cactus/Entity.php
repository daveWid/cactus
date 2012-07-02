<?php

namespace Cactus;

/**
 * An entity class.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Entity
{
	/**
	 * @var   array   The data for this object
	 */
	protected $data = array();

	/**
	 * @var   boolean  Is this a new object?
	 */
	protected $is_new = false;

	/**
	 * @var   array    An array of data that has been modified
	 */
	protected $modified_data = array();

	/**
	 * Creates a new \Cactus\Entity.
	 *
	 * @param  array  Data for a new object.
	 */
	public function __construct(array $data = null)
	{
		if ($data !== null)
		{
			$this->setArray($data);
			$this->is_new = true;
		}
	}

	/**
	 * Checks to see if this is a new object.
	 *
	 * @return   boolean
	 */
	public function isNew()
	{
		return $this->is_new;
	}

	/**
	 * Cleans all of the "modified" fields
	 *
	 * @return   \Cactus\Entity
	 */
	public function clean()
	{
		$this->modified_data = array();
		return $this;
	}

	/**
	 * Returns an array of data.
	 *
	 * @return   array
	 */
	public function asArray()
	{
		return $this->data;
	}

	/**
	 * Returns a json representation of this object.
	 *
	 * @param  string $options  Any json_encode options.
	 * @return string
	 */
	public function asJSON($options = 0)
	{
		return json_encode($this->data, $options);
	}

	/**
	 * Gets only the data that has been modified
	 *
	 * @return   array
	 */
	public function getModifiedData()
	{
		return $this->modified_data;
	}

	/**
	 * Sets key/value pairs in the entity given the array.
	 *
	 * If you only want to set one value at a time use set()
	 *
	 * @param  array $data  The data to set
	 * @return \Cactus\Entity
	 */
	public function setArray(array $data)
	{
		foreach ($data as $key => $value)
		{
			$this->{$key} = $value;
		}

		return $this;
	}

	/**
	 * The magic 'get' method.
	 *
	 * This function will first check for a get{ucfirst($name)} function. If that
	 * isn't set, then it will return a call to get.
	 *
	 * @param  string $name  The name of the property to fetch.
	 * @return mixed         The found value or false if the propery wasn't found
	 */
	public function __get($name)
	{
		$method = "get".ucfirst($name);

		if (method_exists($this, $method))
		{
			return $this->{$method}();
		}
		else
		{
			if (isset($this->data[$name]))
			{
				return $this->data[$name];
			}
			else
			{
				trigger_error(get_called_class()."::{$name} is an undefined property");
			}
		}
	}

	/**
	 * The magic 'set' method.
	 *
	 * This function will look for a set{ucfirst($name)} function. If it isn't
	 * found it will pipe it through to set.
	 *
	 * @param  string $name  The name of the property to set
	 * @param  mixed  $value The value to set
	 */
	public function __set($name, $value)
	{
		$method = "set".ucfirst($name);

		if (method_exists($this, $method))
		{
			$this->{$method}($value);
		}
		else
		{
			if ( ! isset($this->data[$name]) OR $this->data[$name] !== $value)
			{
				$this->modified_data[$name] = $value;
			}

			$this->data[$name] = $value;
		}
	}

}
