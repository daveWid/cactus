<?php defined('SYSPATH') or die('No direct script access.');

abstract class DAO implements ArrayAccess
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
	 * Checks to see if the data is valid
	 *
	 * @return   boolean   Does this object contain valid data?
	 */
	public function valid()
	{
		if ($this->_validation === null)
		{
			$this->_validation = $this->validation_rules(new Validation);
		}

		return $this->_validation->check();
	}

	/**
	 * Sets and returns validation for this object
	 *
	 * @param   Validation   $valid   The validation object to add rules to
	 * @return  Validation            A validation object for this data structure
	 */
	abstract protected function validation_rules(Validation $valid);

	/**
	 * Reading data from inaccessible properties
	 *
	 * @param   type   $name   The name of the property to get
	 * @return  mixed          The value of the property, or null if not found 
	 */
	public function __get($name)
	{
		return $this->offsetExists($name) ? $this->offsetGet($name) : null;
	}

	/**
	 * Write data to inaccessible properties.
	 *
	 * @param   string   $name   The property name
	 * @param   mixed    $value  The value to set
	 */
	public function __set($name, $value)
	{
		$this->_data[$name] = $value;
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
		$this->_data[$offset] = $value;
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
