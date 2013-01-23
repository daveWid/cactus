<?php

/**
 * Testing the MySQL Mapper class.
 *
 * @package   Cactus
 * @author    Dave Widmer <dave@davewidmer.net>
 */
class MySQLMapperTest extends DatabaseTest
{
	public $mapper;

	public function setUp()
	{
		$adapter = new \Cactus\Adapter\PDO($this->getConnection()->getConnection());
		$this->mapper = new UserMapper($adapter);
	}

	public function testGet()
	{
		$user = $this->mapper->get(1);
		$this->assertInstanceOf("\Cactus\Entity", $user);
	}

	public function testGetNoResultIsNull()
	{
		$user = $this->mapper->get(100);
		$this->assertNull($user);
	}

	public function testConversion()
	{
		$user = $this->mapper->get(1);
		$this->assertInstanceOf("DateTime", $user->create_date);
	}

	public function testAll()
	{
		$result = $this->mapper->all();
		$this->assertSame(2, $result->count());
	}

	public function testFind()
	{
		$search = array(
			'name' => "Dave"
		);

		$result = $this->mapper->find($search);
		$this->assertSame(1, $result->count());
	}

	public function testCreate()
	{
		$user = new \Cactus\Entity(array(
			'name' => "Watson",
			'password' => 'meow'
		));

		$this->mapper->save($user);
		$this->assertFalse($user->isNew());
	}

	public function testCreateWithConversion()
	{
		$user = new \Cactus\Entity(
			array(
				'name' => "Biggie",
				'password' => "purrrr....",
				'create_date' => date("Y-m-d H:i:s")
			),
			$this->mapper->getColumns()
		);

		$this->mapper->save($user);
		$this->assertFalse($user->isNew());
	}

	public function testUpdate()
	{
		$user = $this->mapper->get(1);
		$user->name = "David";

		$this->mapper->save($user);
		$this->assertSame("David", $user->name);
	}


	public function testUpdateWithConversion()
	{
		$user = $this->mapper->get(1);
		$user->create_date = date("Y-m-d H:i:s");

		$this->mapper->save($user);
		$this->assertSame("David", $user->name);
	}


	public function testDelete()
	{
		$user = $this->mapper->get(1);
		$this->mapper->delete($user);

		$this->assertNull($user);
	}

}
