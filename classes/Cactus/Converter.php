<?php

namespace Cactus;

/**
 * Converts string over to native php data types.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Converter
{
	/**
	 * Converts a string value to boolean.
	 *
	 * @param  string $value  A 0 or 1 as a string
	 * @return boolean
	 */
	public static function boolean($value)
	{
		return $value === "0" ? false : true;
	}

	/**
	 * Converts a string date to a DateTime object.
	 *
	 * @param  string $value  A Y-m-d date as a string
	 * @return \DateTime
	 */
	public static function date($value)
	{
		return \DateTime::createFromFormat("Y-m-d", $value);
	}

	/**
	 * Converts a string date to a DateTime object.
	 *
	 * @param  string $value  A Y-m-d H:i:s date as a string
	 * @return \DateTime
	 */
	public static function dateTime($value)
	{
		return \DateTime::createFromFormat("Y-m-d H:i:s", $value);
	}

	/**
	 * Converts a string float value to a real float.
	 *
	 * @param  string $value The float value as a string
	 * @return float 
	 */
	public static function float($value)
	{
		return (float) $value;
	}

	/**
	 * Converts a string integer value to a real integer.
	 *
	 * @param  string $value The integer value as a string
	 * @return float 
	 */
	public static function integer($value)
	{
		return (int) $value;
	}

	/**
	 * Converts a string time to a DateTime object.
	 *
	 * @param  string $value  A H:i:s time as a string
	 * @return \DateTime
	 */
	public static function time($value)
	{
		return \DateTime::createFromFormat("H:i:s", $value);
	}

}
