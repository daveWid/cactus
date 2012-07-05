<?php

/**
 * Testing the Gamut of Field classes
 *
 * @package   Cactus
 * @author    Dave Widmer <dave@davewidmer.net>
 */
class ConverterTest extends PHPUnit_Framework_TestCase
{
	public function testBooleanFalse()
	{
		$this->assertFalse(\Cactus\Converter::boolean("0"));
	}

	public function testBooleanTrue()
	{
		$this->assertTrue(\Cactus\Converter::boolean("1"));
	}

	public function testDate()
	{
		$date = "2012-07-05";
		$this->assertInstanceOf("DateTime", \Cactus\Converter::date($date));
	}

	public function testDateTime()
	{
		$date = "2012-07-05 15:20:00";
		$this->assertInstanceOf("DateTime", \Cactus\Converter::dateTime($date));
	}

	public function testFloat()
	{
		$this->assertSame(50.35, \Cactus\Converter::float("50.35"));
	}

	public function testInteger()
	{
		$this->assertSame(50, \Cactus\Converter::integer("50"));
	}

	public function testTime()
	{
		$time = "15:20:00";
		$this->assertInstanceOf("DateTime", \Cactus\Converter::time($time));
	}

}
