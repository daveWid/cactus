<?php

/**
 * Testing the PDO Adapter
 *
 * @package   Cactus
 * @author    Dave Widmer <dave@davewidmer.net>
 */
class PDOAdapterTest extends DatabaseTest
{
	public $pdo;

	public function setUp()
	{
		parent::setUp();

		$this->pdo = new \Cactus\Adapter\PDO($this->pdo = $this->getConnection()->getConnection());
	}

	public function testSelect()
	{
		$query = "SELECT * FROM user WHERE user_id = 1";
		$result = $this->pdo->select($query);

		$this->assertInternalType('array', $result);
	}

	public function testInsert()
	{
		$query = "INSERT INTO user (name, password) VALUES ('Watson', 'meow')";
		list($id, $affected) = $this->pdo->insert($query);

		$this->assertSame(3, $id);
		$this->assertSame(1, $affected);
	}

	public function testUpdate()
	{
		$query = "UPDATE user SET name = 'David' WHERE user_id = 1";
		$affected = $this->pdo->update($query);

		$this->assertSame(1, $affected);
	}

	public function testDelete()
	{
		$query = "DELETE FROM user WHERE user_id = 1";
		$affected = $this->pdo->delete($query);

		$this->assertSame(1, $affected);
	}

	public function testEmptyResult()
	{
		$query = "SELECT * FROM user WHERE user_id = 100";
		$result = $this->pdo->select($query);

		$this->assertSame(0, count($result));
	}

	/**
	 * @expectedException \Cactus\Exception 
	 */
	public function testException()
	{
		$bad_query = "HERP A DERP!";
		$this->pdo->select($bad_query);
	}

	public function testQueries()
	{
		$queries = array(
			'SELECT COUNT(*) AS num FROM user',
			'SELECT * FROM user WHERE user_id = 1'
		);

		foreach ($queries as $query)
		{
			$this->pdo->select($query);
		}

		$this->assertSame($queries, $this->pdo->getQueries());
	}

}
