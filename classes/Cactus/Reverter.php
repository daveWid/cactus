<?php

namespace Cactus;

/**
 * Reverts php data types back to strings.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Reverter
{
	/**
	 * Converts a boolean value to string.
	 *
	 * @param  string $value  A 0 or 1 as a string
	 * @return boolean
	 */
	public static function boolean($value)
	{
		return $value === true ? '1' : '0';
	}

	/**
	 * Converts a string to a "string"?
	 *
	 * @param  string $value  The string value
	 * @return string
	 */
	public static function string($value)
	{
		return (string) $value;
	}

	/**
	 * Converts a DateTime object string date to a string.
	 *
	 * @param  string $value  A Y-m-d date as a string
	 * @return \DateTime
	 */
	public static function date($value)
	{
		$date = new \DateTime($value);
		return $date->format("Y-m-d");
	}

	/**
	 * Converts a DateTime object to a string date.
	 *
	 * @param  string $value  A Y-m-d H:i:s date as a string
	 * @return \DateTime
	 */
	public static function dateTime($value)
	{
		$date = new \DateTime($value);
		return $date->format("Y-m-d H:i:s");
	}

	/**
	 * Converts a float to a string.
	 *
	 * @param  string $value The float value as a string
	 * @return float 
	 */
	public static function float($value)
	{
		return (string) $value;
	}

	/**
	 * Converts an integer to a string.
	 *
	 * @param  string $value The integer value as a string
	 * @return float 
	 */
	public static function integer($value)
	{
		return (string) $value;
	}

	/**
	 * Converts a DateTime object to a string.
	 *
	 * @param  string $value  A H:i:s time as a string
	 * @return \DateTime
	 */
	public static function time($value)
	{
		return $value->format("H:i:s");
	}

}
