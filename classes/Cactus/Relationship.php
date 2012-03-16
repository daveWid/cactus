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
	const HAS_ONE = "HasOne";

	/**
	 * A has may relationship
	 */
	const HAS_MANY = "HasMany";

	/**
	 * Creates a Relationship based on a configuration array.
	 *
	 * @param array  $config The relationship config
	 * @param string $value  The value to join the relation tables
	 * @return \Cactus\Relationship
	 */
	public static function factory(array $config, $value)
	{
		$class = "Cactus\\Relationship\\{$config['type']}";
		return new $class($config['column'], $value, $config['driver']);
	}

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
	 * @param   string   $column   The column that holds the relationship
	 * @param   int      $value    The primary key value
	 * @param   string   $driver   The name of the Driver to use
	 */
	public function __construct($column, $value, $driver)
	{
		$this->column = $column;
		$this->value = $value;
		$this->driver($driver);
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

		$this->driver = is_string($name) ? new $name : $name;
		return $this;
	}

	/**
	 * Sets the data for the result set. Useful for eager loading.
	 *
	 * @param mixed $data  The data to set the result to
	 */
	public function set_result($data)
	{
		$this->result = $data;
		$this->has_result = true;
	}

	/**
	 * Gets the result set for the relationship.
	 *
	 * @return mixed   The result data
	 */
	abstract public function result();

}
