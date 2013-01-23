<?php

/**
 * Testing the Revert action
 *
 * @package   Cactus
 * @author    Dave Widmer <dave@davewidmer.net>
 */
class ReverterTest extends PHPUnit_Framework_TestCase
{
	public $reverter;

	public function setUp()
	{
		$this->reverter = new \Cactus\Reverter;
	}

	public function testBooleanFalse()
	{
		$this->assertSame("0", $this->reverter->boolean(false));
	}

	public function testBooleanTrue()
	{
		$this->assertSame("1", $this->reverter->boolean(true));
	}

	public function testDate()
	{
		$time ="2012-07-05";
		$date = DateTime::createFromFormat("Y-m-d", $time);

		$this->assertSame($time, $this->reverter->date($date));
	}

	public function testDateTime()
	{
		$time = "2012-07-05 15:20:00";
		$date = DateTime::createFromFormat("Y-m-d H:i:s", $time);

		$this->assertSame($time, $this->reverter->dateTime($date));
	}

	public function testFloat()
	{
		$this->assertSame('50.35', $this->reverter->float(50.35));
	}

	public function testInteger()
	{
		$this->assertSame('50', $this->reverter->integer(50));
	}

	public function testTime()
	{
		$time = "15:20:00";
		$date = DateTime::createFromFormat("H:i:s", $time);

		$this->assertSame($time, $this->reverter->time($date));
	}

}
