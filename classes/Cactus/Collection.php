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
	 * @var array  The internal result array
	 */
	private $data = array();

	/**
	 * Adds data to the collection.
	 *
	 * @param array $data  The data to set
	 */
	public function __construct(array $data = array())
	{
		$this->data = $data;
	}

	/**
	 * Gets the number of items in the result set.
	 *
	 * @return int  The number of items in the result set
	 */
	public function count()
	{
		return count($this->data);
	}

	/**
	 * Add an item to the result set.
	 *
	 * @param mixed $item  The item to add to the result set
	 */
	public function add($item)
	{
		$this->data[] = $item;
	}

	/**
	 * Return the current element
	 *
	 * @link http://php.net/manual/en/iterator.current.php
	 * @return   mixed    Can return any type.
	 */
	public function current()
	{
		return current($this->data);
	}

	/**
	 * Move forward to next element
	 *
	 * @link http://php.net/manual/en/iterator.next.php
	 */
	public function next()
	{
		next($this->data);
	}

	/**
	 * Return the key of the current element
	 *
	 * @link http://php.net/manual/en/iterator.key.php
	 * @return   scalar   scalar on success, integer 0 on failure.
	 */
	public function key()
	{
		return key($this->data);
	}

	/**
	 * Checks if current position is valid
	 *
	 * @link http://php.net/manual/en/iterator.valid.php
	 * @return   boolean   Returns true on success or false on failure.
	 */
	public function valid()
	{
		$key = key($this->data);
        return ($key !== NULL AND $key !== FALSE); 
	}

	/**
	 * Rewind the Iterator to the first element
	 *
	 * @link http://php.net/manual/en/iterator.rewind.php
	 */
	public function rewind()
	{
		reset($this->data);
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
		return isset($this->data[$offset]);
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
		return $this->data[$offset];
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
		$this->data[$offset] = $value;
	}

	/**
	 * Offset to unset
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset  The offset to unset.
	 */
	public function offsetUnset($offset)
	{
		unset($this->data[$offset]);
	}

}
