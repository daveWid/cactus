<?php

namespace Cactus\Relationship;

/**
 * A HasMany relationship.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class HasMany extends \Cactus\Relationship implements \Countable, \IteratorAggregate
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
		return $this->result();
	}

}
