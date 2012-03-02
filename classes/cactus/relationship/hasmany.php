<?php

namespace Cactus\Relationship;

/**
 * A HasMany relationship.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class HasMany extends \Cactus\Relationship implements \Countable, \IteratorAggregate, \ArrayAccess
{
	/**
	 * Gets the result set.
	 */
	public function result()
	{
		if ( ! $this->has_result)
		{
			$this->has_result = true;

			$this->result = $this->driver->find(array(
				$this->column => $this->value
			));
		}

		return $this->result;
	}

	/**
	 * Count elements of an object
	 *
	 * @link http://php.net/manual/en/countable.count.php
	 * @return   int    The custom count as an integer.
	 */
	public function count()
	{
		return count($this->result()->data());
	}

	/**
	 * Retrieve an external iterator
	 *
	 * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return   Traversable   An instance of an object implementing Iterator or Traversable
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->result()->data());
	}

	/**
	 * Whether a offset exists.
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset An offset to check for.
	 * @return boolean Returns true on success or false on failure. The return value will be casted to boolean if non-boolean was returned.
	 */
	public function offsetExists($offset)
	{
		$this->result();
		return isset($this->result[$offset]);
	}

	/**
	 * Offset to retrieve.
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset The offset to retrieve.
	 * @return mixed Can return all value types.
	 */
	public function offsetGet($offset)
	{
		$this->result();
		return $this->result[$offset];
	}

	/**
	 * Offset to set.
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset The offset to assign the value to.
	 * @param mixed $value  The value to set.
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		$this->result();
		$this->result[$offset] = $value;
	}

	/**
	 * Offset to unset.
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset The offset to unset.
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		$this->result();
		unset($this->result[$offset]);
	}

}
