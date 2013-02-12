<?php

class TasksTest extends DatabaseTest
{
	public $tasks;
	public $path;
	public $adapter;

	public function setUp()
	{
		$adapter = new \Cactus\Adapter\PDO($this->getConnection()->getConnection());

		$this->path = dirname(__FILE__).DIRECTORY_SEPARATOR.'tasks';
		$this->tasks = new \Cactus\Tasks($this->path, $adapter);
		$this->adapter = $adapter;
	}

	public function testPathAddsDS()
	{
		$this->assertSame($this->path.DIRECTORY_SEPARATOR, $this->tasks->getPath());
	}

	public function testMigrate()
	{
		$output = $this->tasks->migrate();
		$this->assertSame(array(
			'Migration #001 Setup: Success'
		), $output);
	}

	public function testMigrationWorked()
	{
		$result = $this->adapter->select("SELECT * FROM migration_user LIMIT 1");
		$this->assertEmpty($result); // <- no data in there yet...
	}

	public function testRollback()
	{
		$output = $this->tasks->rollback();
		$this->assertSame(array(
			'Rollback #001 Setup: Success'
		), $output);
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
		$output = $this->tasks->migrate('001');
		$this->assertEmpty($output);
	}

	public function testNoRollbacksRun()
	{
		$output = $this->tasks->rollback('001');
		$this->assertEmpty($output);
	}

}
