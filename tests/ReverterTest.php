<?php

/**
 * Testing the Revert action
 *
 * @package   Cactus
 * @author    Dave Widmer <dave@davewidmer.net>
 */
class ReverterTest extends PHPUnit_Framework_TestCase
{
	public function testBooleanFalse()
	{
		$this->assertSame("0", \Cactus\Reverter::boolean(false));
	}

	public function testBooleanTrue()
	{
		$this->assertSame("1", \Cactus\Reverter::boolean(true));
	}

	public function testDate()
	{
		$time ="2012-07-05";
		$date = DateTime::createFromFormat("Y-m-d", $time);

		$this->assertSame($time, \Cactus\Reverter::date($date));
	}

	public function testDateTime()
	{
		$time = "2012-07-05 15:20:00";
		$date = DateTime::createFromFormat("Y-m-d H:i:s", $time);

		$this->assertSame($time, \Cactus\Reverter::dateTime($date));
	}

	public function testFloat()
	{
		$this->assertSame('50.35', \Cactus\Reverter::float(50.35));
	}

	public function testInteger()
	{
		$this->assertSame('50', \Cactus\Reverter::integer(50));
	}

	public function testTime()
	{
		$time = "15:20:00";
		$date = DateTime::createFromFormat("H:i:s", $time);

		$this->assertSame($time, \Cactus\Reverter::time($date));
	}

}
