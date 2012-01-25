<?php

namespace Cactus\Field;

/**
 * MySQL Field Types
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class MySQL
{
	/**
	 * Boolean
	 */
	const BOOLEAN = 0;

	/**
	 * Integer
	 */
	const INT = 1;

	/**
	 * Float
	 */
	const FLOAT = 2;

	/**
	 * Date in Y-m-d format
	 */
	const DATE = 4;

	/**
	 * Time in H:i:s format
	 */
	const TIME = 8;

	/**
	 * Datetime in Y-m-d H:i:s format
	 */
	const DATETIME = 16;

	/**
	 * Year
	 */
	const YEAR = 32;

	/**
	 * Varchar (strings)
	 */
	const VARCHAR = 64;

	/**
	 * Converts a value of the given type.
	 *
	 * @param string $type   The database type to convert to
	 * @param mixed  $value  The value from the database (usually strings)
	 */
	public static function convert($type, $value)
	{
		switch ($type) 
		{
			case static::BOOLEAN:
				$value = ((int) $value === 1);
			break;
			case static::INT:
			case static::YEAR:
				$value = (int) $value;
			break;
			case static::FLOAT:
				$value = (float) $value;
			break;
			case static::DATE:
				$value = DateTime::createFromFormat("Y-m-d", $value);
			break;
			case static::TIME:
				$value = DateTime::createFromFormat("H:i:s", $value);
			break;
			case static::DATETIME:
				$value = DateTime::createFromFormat("Y-m-d H:i:s", $value);
			break;
			default:
		}

		return $value;
	}

}
