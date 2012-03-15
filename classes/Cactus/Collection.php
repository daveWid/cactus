<?php

namespace Cactus;

/**
 * A collection of Cactus Entities
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Collection implements \Iterator, \Countable, \ArrayAccess
{
	/**
	 * @var   array    The internal collection array
	 */
	protected $collection = array();

	/**
	 * Creates a new DataMapper_Collection object
	 *
	 * @param   array   $data   The data to set
	 */
	public function __construct(array $data = array())
	{
		$this->collection = $data;
	}

	/**
	 * Counts the number of items in the collection.
	 *
	 * @return int  The number of items in the collection
	 */
	public function count()
	{
		return count($this->collection);
	}

	/**
	 * Getter/setter for the data in the collection.
	 *
	 * @param   array   Array of data to set
	 * @return  mixed   Array on get/$this on set
	 */
	public function data($data = null)
	{
		if ($data === null)
		{
			return $this->collection;
		}

		$this->collection = array_merge($this->collection, $data);
		return $this;
	}

	/**
	 * Return the current element
	 *
	 * @link http://php.net/manual/en/iterator.current.php
	 * @return   mixed    Can return any type.
	 */
	public function current()
	{
		return current($this->collection);
	}

	/**
	 * Move forward to next element
	 *
	 * @link http://php.net/manual/en/iterator.next.php
	 */
	public function next()
	{
		next($this->collection);
	}

	/**
	 * Return the key of the current element
	 *
	 * @link http://php.net/manual/en/iterator.key.php
	 * @return   scalar   scalar on success, integer 0 on failure.
	 */
	public function key()
	{
		return key($this->collection);
	}

	/**
	 * Checks if current position is valid
	 *
	 * @link http://php.net/manual/en/iterator.valid.php
	 * @return   boolean   Returns true on success or false on failure.
	 */
	public function valid()
	{
		$key = key($this->collection);
        return ($key !== NULL AND $key !== FALSE); 
	}

	/**
	 * Rewind the Iterator to the first element
	 *
	 * @link http://php.net/manual/en/iterator.rewind.php
	 */
	public function rewind()
	{
		reset($this->collection);
	}

	/**
	 * Whether a offset exists.
	 *
	 * @link   http://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param  mixed $offset  An offset to check for
	 * @return boolean        true on success or false on failure.
	 */
	public function offsetExists($offset)
	{
		return isset($this->collection[$offset]);
	}

	/**
	 * Offset to retrieve
	 *
	 * @link   http://php.net/manual/en/arrayaccess.offsetget.php
	 * @param  mixed $offset  The offset to retrieve.
	 * @return mixed          Can return all value types.
	 */
	public function offsetGet($offset)
	{
		return $this->collection[$offset];
	}

	/**
	 * Offset to set
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset  The offset to assign the value to.
	 * @param mixed $value   The value to set.
	 */
	public function offsetSet($offset, $value)
	{
		$this->collection[$offset] = $value;
	}

	/**
	 * Offset to unset
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset  The offset to unset.
	 */
	public function offsetUnset($offset)
	{
		unset($this->collection[$offset]);
	}

}