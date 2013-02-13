<?php

class MigrateTest extends DatabaseTest
{
	public $migrate;
	public $path;
	public $adapter;

	public function setUp()
	{
		$adapter = new \Cactus\Adapter\PDO($this->getConnection()->getConnection());

		$this->path = dirname(__FILE__).DIRECTORY_SEPARATOR.'tasks'.DIRECTORY_SEPARATOR.'migrations';
		$this->migrate = new \Cactus\Task\Migrate($this->path, $adapter);
		$this->adapter = $adapter;
	}

	public function testPathAddsDS()
	{
		$this->assertSame($this->path.DIRECTORY_SEPARATOR, $this->migrate->getPath());
	}

	public function testMigrate()
	{
		$output = $this->migrate->migrate();
		$this->assertSame(array(
			'name' => "Setup",
			'id' => '001',
			'success' => true
		), $output[0]);
	}

	public function testMigrationWorked()
	{
		$result = $this->adapter->select("SELECT * FROM migration_user LIMIT 1");
		$this->assertEmpty($result); // <- no data in there yet...
	}

	public function testRollback()
	{
		$output = $this->migrate->rollback();
		$this->assertSame(array(
			'name' => "Setup",
			'id' => '001',
			'success' => true
		), $output[0]);
	}

	/**
	 * @expectedException \Cactus\Exception
	 */
	public function testRollbackWorked()
	{
		$result = $this->adapter->select("SELECT * FROM migration_user LIMIT 1");
	}

	public function testNoMigrationsRun()
	{
		$output = $this->migrate->migrate('001');
		$this->assertEmpty($output);
	}

	public function testNoRollbacksRun()
	{
		$output = $this->migrate->rollback('001');
		$this->assertEmpty($output);
	}

}
