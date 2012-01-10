<?php
namespace DataMapper;

/**
 * A HasMany relationship for DataMapper.
 *
 * @package    DataMapper
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class Relationship
{
	/**
	 * A has one relationship
	 */
	const HAS_ONE = "hasone";

	/**
	 * A has may relationship
	 */
	const HAS_MANY = "hasmany";

	/**
	 * @var   mixed    The result set for the relationship
	 */
	protected $result = null;

	/**
	 * @var   boolean  Has the result set been grabbed yet?
	 */
	protected $has_result = false;

	/**
	 * @var   int   The value of the column that forms the relationship
	 */
	protected $value;

	/**
	 * @var   DataMapper   The mapper to get results with
	 */
	protected $mapper;

	/**
	 * @var   array    How the tables are connected
	 */
	protected $column;

	/**
	 * Creates a new DataMapper_Relationship object
	 *
	 * @param   int      $value    The primary key value
	 * @param   string   $mapper   The name of the DataMapper to use
	 * @param   string   $column   The column that holds the relationship
	 */
	public function __construct($value, $mapper, $column)
	{
		$this->value = $value;
		$this->mapper($mapper);
		$this->column = $column;
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
			return $this->mapper;
		}

		$this->mapper = new $name;
		return $this;
	}

	/**
	 * Gets the result set for the relationship
	 */
	abstract public function result();

}
