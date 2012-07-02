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
		$result = $this->data->select("/libraries/library", "MockEntity");
		$this->assertFalse(empty($result));
	}

}
