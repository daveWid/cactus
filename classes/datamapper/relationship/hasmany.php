<?php defined('SYSPATH') or die('No direct script access.');
/**
 * A HasMany relationship for DataMapper.
 *
 * @package    DataMapper
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class DataMapper_Relationship_HasMany extends DataMapper_Relationship implements Countable, IteratorAggregate
{
	/**
	 * Gets the result set.
	 */
	public function result()
	{
		if ( ! $this->_has_result)
		{
			$this->_has_result = true;

			$this->_result = $this->_mapper->find(array(
				$this->_column => $this->_value
			));
		}

		return $this->_result;
	}

	/**
	 * Count elements of an object
	 *
	 * @link http://php.net/manual/en/countable.count.php
	 * @return   int    The custom count as an integer.
	 */
	public function count()
	{
		return count($this->result());
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
