<?php
namespace DataMapper;

/**
 * A collection of DataMapper Objects
 *
 * @package    DataMapper
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Collection implements \Iterator
{
	/**
	 * @var   array    The internal collection array
	 */
	protected $_collection = array();

	/**
	 * Creates a new DataMapper_Collection object
	 *
	 * @param   array   $data   The data to set
	 */
	public function __construct(array $data = array())
	{
		$this->_collection = $data;
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
			return $this->_collection;
		}

		$this->_collection = array_merge($this->_collection, $data);
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
		return current($this->_collection);
	}

	/**
	 * Move forward to next element
	 *
	 * @link http://php.net/manual/en/iterator.next.php
	 */
	public function next()
	{
		next($this->_collection);
	}

	/**
	 * Return the key of the current element
	 *
	 * @link http://php.net/manual/en/iterator.key.php
	 * @return   scalar   scalar on success, integer 0 on failure.
	 */
	public function key()
	{
		return key($this->_collection);
	}

	/**
	 * Checks if current position is valid
	 *
	 * @link http://php.net/manual/en/iterator.valid.php
	 * @return   boolean   Returns true on success or false on failure.
	 */
	public function valid()
	{
		$key = key($this->_collection);
        return ($key !== NULL AND $key !== FALSE); 
	}

	/**
	 * Rewind the Iterator to the first element
	 *
	 * @link http://php.net/manual/en/iterator.rewind.php
	 */
	public function rewind()
	{
		reset($this->_collection);
	}

}