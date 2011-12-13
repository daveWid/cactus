<?php defined('SYSPATH') or die('No direct script access.');
/**
 * A HasMany relationship for DataMapper.
 *
 * @package    DataMapper
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class DataMapper_Relationship implements Countable, IteratorAggregate
{
	/**
	 * @var   mixed    The result set for the relationship
	 */
	protected $_result = null;

	/**
	 * @var   int   The value of the column that forms the relationship
	 */
	protected $_value;

	/**
	 * @var   DataMapper   The mapper to get results with
	 */
	protected $_mapper;

	/**
	 * @var   array    How the tables are connected
	 */
	protected $_column;

	/**
	 * Creates a new DataMapper_Relationship object
	 *
	 * @param   int      $value    The primary key value
	 * @param   string   $mapper   The name of the DataMapper to use
	 * @param   string   $column   The column that holds the relationship
	 */
	public function __construct($value, $mapper, $column)
	{
		$this->_value = $value;
		$this->mapper($mapper);
		$this->_column = $column;
	}

	/**
	 * Getter/Setter for the mapper.
	 *
	 * @param   string   $name   The name of the mapper
	 * @return  $this
	 */
	public function mapper($name = null)
	{
		if ($name === null)
		{
			return $this->_mapper;
		}

		$this->_mapper = new $name;
		return $this;
	}

	/**
	 * Gets the result set for the relationship
	 */
	abstract public function result();

}
