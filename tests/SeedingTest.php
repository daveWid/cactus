<?php

class SeedingTest extends DatabaseTest
{
	public $adapter;
	public $migrate;
	public $seed;

	public function setUp()
	{
		parent::setUp();
		$adapter = new \Cactus\Adapter\PDO($this->getConnection()->getConnection());

		$path = dirname(__FILE__).DIRECTORY_SEPARATOR.'tasks'.DIRECTORY_SEPARATOR;
		$this->migrate = new \Cactus\Task\Migrate($path.'migrations', $adapter);
		$this->seed = new \Cactus\Task\Seed($path.'seed', $adapter);
		$this->adapter = $adapter;

		$this->migrate->migrate();
	}

	public function testSingle()
	{
		$output = $this->seed->single('MigrationUserSeed');
		$this->assertTrue($output);
	}

	/**
	 * @expectedException \Cactus\Exception
	 */
	public function testMissingFileThrowsException()
	{
		$this->seed->single('NotFoundSeed');
	}

	public function testMultiple()
	{
		$output = $this->seed->multiple(array(
			'MigrationUserSeed',
			'UserSeed'
		));

		$expected = array(
			'MigrationUserSeed' => true,
			'UserSeed' => true
		);

		$this->assertSame($expected, $output);
	}

	public function testAll()
	{
		$output = $this->seed->all();

		$expected = array(
			'MigrationUserSeed' => true,
			'UserSeed' => true
		);

		$this->assertSame($expected, $output);
	}

	public function tearDown()
	{
		parent::tearDown();
		$this->migrate->rollback();
	}

}
