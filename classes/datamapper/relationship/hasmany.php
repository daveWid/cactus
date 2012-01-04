<?php
namespace DataMapper\Relationship;

/**
 * A HasMany relationship for DataMapper.
 *
 * @package    DataMapper
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class HasMany extends \DataMapper\Relationship implements \Countable, \IteratorAggregate
{
	/**
	 * Gets the result set.
	 */
	public function result()
	{
		if ( ! $this->has_result)
		{
			$this->has_result = true;

			$this->result = $this->mapper->find(array(
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
