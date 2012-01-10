<?php
namespace DataMapper;

/**
 * The base Object class for data rows.
 *
 * @package    DataMapper
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class Object implements \ArrayAccess
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
	 * @var   mixed   The validation object
	 */
	protected $validation = null;

	/**
	 * Creates a new DataMapper\Object.
	 *
	 * @param  array  Data for a new object.
	 */
	public function __construct(array $data = null)
	{
		if ($data !== null)
		{
			$this->set($data);
			$this->is_new = true;
		}
	}

	/**
	 * Checks to see if this is a new object.
	 *
	 * @return   boolean
	 */
	public function is_new()
	{
		return $this->is_new;
	}

	/**
	 * Cleans all of the "modified" fields
	 *
	 * @return   $this
	 */
	public function clean()
	{
		$this->validation = null;
		$this->modified_data = array();
		return $this;
	}

	/**
	 * Returns all of the data in the object
	 *
	 * @return   array
	 */
	public function data()
	{
		return $this->data;
	}

	/**
	 * Gets only the data that is modified
	 *
	 * @return   array
	 */
	public function modified()
	{
		return $this->modified_data;
	}

	/**
	 * Checks to see if the data is valid
	 *
	 * @return   boolean   Does this object contain valid data?
	 */
	public function validate()
	{
		return true; // No validation by default
	}

	/**
	 * Gets any validation errors
	 *
	 * @uses    Validation::errors
	 * @param   type     $file        The path to the message file
	 * @param   boolean  $translate   Translate the errors?
	 * @return  array
	 */
	public function errors($file = null, $translate = true)
	{
		return array(); // No validation by default, so no checking is done...
	}

	/**
	 * Sets the validation rules for this object.
	 *
	 * @param   mixed   $valid   The current validation rules
	 * @return  mixed
	 */
	protected function validation_rules($valid)
	{
		return $valid; // no default validation...
	}

	/**
	 * Gets the value of an instance variable.
	 *
	 * @param   string   $name     The property name to fetch
	 * @param   mixed    $default  The default value if the key isn't found
	 * @return  mixed
	 */
	public function get($name, $default = null)
	{
		return $this->offsetExists($name) ? $this->data[$name] : $default;
	}

	/**
	 * Sets a property value, or an array of values.
	 *
	 * @param   string|array   $name    The property name or array of property => values
	 * @param   mixed          $value   The value to set
	 * @return  $this
	 */
	public function set($name, $value = null)
	{
		if ( ! is_array($name))
		{
			$name = array($name => $value);
		}

		foreach ($name as $key => $value)
		{
			if ( ! $this->get($key, false) OR $this->data[$key] !== $value)
			{
				$this->modified_data[$key] = $value;
			}

			$this->data[$key] = $value;
		}

		return $this;
	}

	/**
	 * Reading data from inaccessible properties
	 *
	 * @param   type   $name   The name of the property to get
	 * @return  mixed          The value of the property, or null if not found 
	 */
	public function __get($name)
	{
		return $this->get($name, null);
	}

	/**
	 * Write data to inaccessible properties.
	 *
	 * @param   string   $name   The property name
	 * @param   mixed    $value  The value to set
	 */
	public function __set($name, $value)
	{
		$this->set($name, $value);
	}

	/**
	 * Whether a offset exists
	 *
	 * @link    http://php.net/manual/en/arrayaccess.offsetexists.php
	 *
	 * @param   mixed   $offset   An offset to check for.
	 * @return  boolean           Returns true on success or false on failure.
	 */
	public function offsetExists($offset)
	{
		return isset($this->data[$offset]);
	}

	/**
	 * Offset to retrieve
	 *
	 * @link    http://php.net/manual/en/arrayaccess.offsetget.php
	 *
	 * @param   mixed   $offset   The offset to retrieve.
	 * @return  mixed             Can return all value types.
	 */
	public function offsetGet($offset)
	{
		return $this->data[$offset];
	}

	/**
	 * Offset to set
	 *
	 * @link    http://php.net/manual/en/arrayaccess.offsetset.php
	 *
	 * @param   mixed   $offset    The offset to assign the value to.
	 * @param   mixed   $value     The value to set.
	 * @return  void 
	 */
	public function offsetSet($offset, $value)
	{
		$this->set($offset, $value);
	}

	/**
	 * Offset to unset
	 * 
	 * @link    http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param   mixed   $offset   The offset to unset.
	 * @return  void
	 */
	public function offsetUnset($offset)
	{
		unset($this->data[$offset]);
	}

}
