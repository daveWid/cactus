<?php

/**
 * Testing the Gamut of Field classes
 *
 * @package   Cactus
 * @author    Dave Widmer <dave@davewidmer.net>
 */
class ConverterTest extends PHPUnit_Framework_TestCase
{
	public $converter;

	public function setUp()
	{
		$this->converter = new \Cactus\Converter;
	}

	public function testBooleanFalse()
	{
		$this->assertFalse($this->converter->boolean("0"));
	}

	public function testBooleanTrue()
	{
		$this->assertTrue($this->converter->boolean("1"));
	}

	public function testDate()
	{
		$date = "2012-07-05";
		$this->assertInstanceOf("DateTime", $this->converter->date($date));
	}

	public function testDateTime()
	{
		$date = "2012-07-05 15:20:00";
		$this->assertInstanceOf("DateTime", $this->converter->dateTime($date));
	}

	public function testFloat()
	{
		$this->assertSame(50.35, $this->converter->float("50.35"));
	}

	public function testInteger()
	{
		$this->assertSame(50, $this->converter->integer("50"));
	}

	public function testTime()
	{
		$time = "15:20:00";
		$this->assertInstanceOf("DateTime", $this->converter->time($time));
	}

}
