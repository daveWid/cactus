<?php

/**
 * Testing out the XML DataSource class.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class DataSourceXMLTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \Cactus\DataSource\XML  The xml datasource to test with
	 */
	public $data;

	public function setUp()
	{
		parent::setUp();

		$file = __DIR__.DIRECTORY_SEPARATOR.'seed.xml';
		$this->data = new \Cactus\DataSource\XML($file);
	}

	public function testSelect()
	{
		$result = $this->data->select("/mysqldump/database/table_data/row", "MockUser");

		$this->assertFalse(empty($result));
		$this->assertSame(2, $result->count());
	}

	/**
	 * @expectedException \Cactus\Exception
	 */
	public function testInsert()
	{
		$this->data->insert("/users/user");
	}

	/**
	 * @expectedException \Cactus\Exception
	 */
	public function testUpdate()
	{
		$this->data->update("/users/user");
	}

	/**
	 * @expectedException \Cactus\Exception
	 */
	public function testDelete()
	{
		$this->data->delete("/users/user");
	}

}
