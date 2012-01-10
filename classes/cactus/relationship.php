<?php

namespace Cactus;

/**
 * Relationship class.
 *
 * @package    Cactus
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
	 * @var   \Cactus\Driver   The driver used to fetch results
	 */
	protected $driver;

	/**
	 * @var   array    How the tables are connected
	 */
	protected $column;

	/**
	 * Creates a new \Cactus\Relationship object
	 *
	 * @param   int      $value    The primary key value
	 * @param   string   $driver   The name of the Driver to use
	 * @param   string   $column   The column that holds the relationship
	 */
	public function __construct($value, $driver, $column)
	{
		$this->value = $value;
		$this->driver($driver);
		$this->column = $column;
	}

	/**
	 * Getter/Setter for the driver.
	 *
	 * @param   string   $name   The name of the driver
	 * @return  $this
	 */
	public function driver($name = null)
	{
		if ($name === null)
		{
			return $this->driver;
		}

		$this->driver = new $name;
		return $this;
	}

	/**
	 * Gets the result set for the relationship
	 */
	abstract public function result();

}
