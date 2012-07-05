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
	 * Converts a string value to boolean.
	 *
	 * @param  string $value  A 0 or 1 as a string
	 * @return boolean
	 */
	public static function boolean($value)
	{
		return $value === true ? '1' : '0';
	}

	/**
	 * Converts a string date to a DateTime object.
	 *
	 * @param  string $value  A Y-m-d date as a string
	 * @return \DateTime
	 */
	public static function date($value)
	{
		return $value->format("Y-m-d");
	}

	/**
	 * Converts a string date to a DateTime object.
	 *
	 * @param  string $value  A Y-m-d H:i:s date as a string
	 * @return \DateTime
	 */
	public static function dateTime($value)
	{
		return $value->format("Y-m-d H:i:s");
	}

	/**
	 * Converts a string float value to a real float.
	 *
	 * @param  string $value The float value as a string
	 * @return float 
	 */
	public static function float($value)
	{
		return (string) $value;
	}

	/**
	 * Converts a string integer value to a real integer.
	 *
	 * @param  string $value The integer value as a string
	 * @return float 
	 */
	public static function integer($value)
	{
		return (string) $value;
	}

	/**
	 * Converts a string time to a DateTime object.
	 *
	 * @param  string $value  A H:i:s time as a string
	 * @return \DateTime
	 */
	public static function time($value)
	{
		return $value->format("H:i:s");
	}

}
