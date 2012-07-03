<?php

/**
 * Testing out the ResultSet class
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class ResultSetTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \Cactus\ResultSet  A result set
	 */
	public $result;

	/**
	 * @var array  A new "result" row for testing purposes
	 */
	public $new_row;

	public function setUp()
	{
		parent::setUp();

		$file = __DIR__.DIRECTORY_SEPARATOR.'seed.xml';
		$this->data = new \Cactus\DataSource\XML($file);

		$this->result = $this->data->select("/libraries/library", "MockEntity");

		$this->new_row = array(
			'name' => "Peyote",
			'author' => 'Dave Widmer',
			'createDate' => '2011-10-02 08:58:23',
			'version' => '0.5.5'
		);
	}

	public function testConstructorWithData()
	{
		$rs = new \Cactus\ResultSet($this->new_row);

		$this->assertSame($this->new_row, $rs->asArray());
	}

	public function testConstructorWithoutData()
	{
		$rs = new \Cactus\ResultSet;
		$this->assertSame(array(), $rs->asArray());
	}

	public function testCounting()
	{
		$this->assertSame(2, $this->result->count());
	}

	public function testAddingResults()
	{
		$this->result->add($this->new_row);
		$this->assertSame(3, $this->result->count());
	}

	public function testLooping()
	{
		$items = 0;
		
		foreach($this->result as $row)
		{
			$this->assertNotEmpty($row);
			$items++;
		}

		$this->assertSame(2, $items);
	}

	public function testArrayAccess()
	{
		$this->result->add($this->new_row);
		$this->assertSame($this->new_row, $this->result[2]);
	}

}
