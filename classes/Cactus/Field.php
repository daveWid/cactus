<?php

namespace Cactus;

/**
 * Converts Database fields into native PHP types.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Field
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
	public function convert($type, $value)
	{
		switch ($type) 
		{
			case self::BOOLEAN:
				$value = ((int) $value === 1);
			break;
			case self::INT:
			case self::YEAR:
				$value = (int) $value;
			break;
			case self::FLOAT:
				$value = (float) $value;
			break;
			case self::DATE:
				$value = \DateTime::createFromFormat("Y-m-d", $value);
			break;
			case self::TIME:
				$value = \DateTime::createFromFormat("H:i:s", $value);
			break;
			case self::DATETIME:
				$value = \DateTime::createFromFormat("Y-m-d H:i:s", $value);
			break;
			default:
		}

		return $value;
	}

}
