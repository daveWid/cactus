<?php
/**
 * The base Object class for data rows.
 *
 * @package    DataMapper
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class DataMapper_Object implements ArrayAccess
{
	/**
	 * @var   array   The data for this object
	 */
	protected $_data = array();

	/**
	 * @var   Validation   The validation object
	 */
	protected $_validation = null;

	/**
	 * @var   boolean     Is this a new object?
	 */
	protected $_is_new = false;

	/**
	 *
	 * @var   array     A list of columns that have been modified
	 */
	protected $_modified_columns = array();

	/**
	 * Creates a new DataMapper_Object.
	 *
	 * @param   boolean   $is_new 
	 */
	public function __construct($is_new = false)
	{
		$this->_is_new = $is_new;
	}

	/**
	 * Checks to see if this is a new object.
	 *
	 * @return   boolean
	 */
	public function is_new()
	{
		return $this->_is_new;
	}

	/**
	 * Cleans all of the "modified" fields
	 *
	 * @return   $this
	 */
	public function clean()
	{
		$this->_modified_columns = array();
		$this->_validation = null;
		return $this;
	}

	/**
	 * Returns all of the data in the object
	 *
	 * @return   array
	 */
	public function data()
	{
		return $this->_data;
	}

	/**
	 * Gets only the data that is modified
	 *
	 * @return   array
	 */
	public function modified()
	{
		$modified = array();
		foreach ($this->_modified_columns as $key)
		{
			$modified[$key] = $this->_data[$key];
		}

		return $modified;
	}

	/**
	 * Checks to see if the data is valid
	 *
	 * @uses     Validation::check
	 * @return   boolean   Does this object contain valid data?
	 */
	public function validate()
	{
		if ($this->_validation === null)
		{
			$this->_validation = $this->_validation_rules(new Validation($this->_data));
		}

		return $this->_validation->check();
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
		return $this->_validation->errors($file, $translate);
	}

	/**
	 * Sets and returns validation for this object
	 *
	 * @param   Validation   $valid   The validation object to add rules to
	 * @return  Validation            A validation object for this data structure
	 */
	abstract protected function _validation_rules(Validation $valid);

	/**
	 * Gets the value of an instance variable.
	 *
	 * @param   string   $name     The property name to fetch
	 * @param   mixed    $default  The default value if the key isn't found
	 * @return  mixed
	 */
	public function get($name, $default = null)
	{
		return $this->offsetExists($name) ? $this->_data[$name] : $default;
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
			if ( ! $this->get($key, false) OR $this->_data[$key] !== $value)
			{
				$this->_modified_columns[] = $key;
			}

			$this->_data[$key] = $value;
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
		return isset($this->_data[$offset]);
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
		return $this->_data[$offset];
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
		unset($this->_data[$offset]);
	}

}
