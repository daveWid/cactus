<?php

namespace Cactus\Tests;

/**
 * Runs the model class throuh its paces.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class ModelTest extends \Cactus\Tests\DatabaseTest
{
	/**
	 * @var \Cactus\Tests\ModelUser  The user model to run tests on.
	 */
	public $model;

	/**
	 * Setup the PDO adapter
	 */
	public function setUp()
	{
		$adapter = new \Cactus\Adapter\PDO;
		$adapter->set_connection($this->getConnection()->getConnection());

		$this->model = new \Cactus\Tests\ModelUser;
		$this->model->set_adapter($adapter);

		parent::setUp();
	}

	/**
	 * See if we can read a single row.
	 */
	public function testGet()
	{
		$this->assertInstanceOf("\\Cactus\\Tests\\User", $this->model->get(1));
	}

	/**
	 * Test to see if null is returned when a row isn't found
	 */
	public function testNonExsitsIsNull()
	{
		$this->assertNull($this->model->get(10));
	}

	/**
	 * Tests a call for all records
	 */
	public function testAll()
	{
		$this->assertInstanceOf("\\Cactus\\Collection", $this->model->all());
	}

	/**
	 * A sample test on the finder.
	 */
	public function testFind()
	{
		$result = $this->model->find(array(
			'first_name' => "Testy",
			'order_by' => array("user_id", "DESC"),
			'limit' => 1
		));

		$this->assertInstanceOf("\\Cactus\\Collection", $result);
	}

	/**
	 * See if we can create a new Entity
	 */
	public function testCreate()
	{
		$data = array(
			'email' => "foo@bar.com",
			'first_name' => "Foo",
			'last_name' => "Bar",
			'password' => "fakepassword",
		);

		$user = new \Cactus\Tests\User($data);
		$this->assertNotSame(false, $this->model->save($user));
	}

	/**
	 * Runs through the read, update, save procedure
	 */
	public function testUpdate()
	{
		$user = $this->model->get(1);

		$user->first_name = "Abe";
		$user->last_name = "Lincoln";

		$this->assertSame(1, $this->model->save($user));
	}

	/**
	 * Deletes (and nulls) an Entity
	 */
	public function testDelete()
	{
		$user = $this->model->get(1);

		$this->assertSame(1, $this->model->delete($user));
		$this->assertNull($user);
	}

	/**
	 * Get the relationships array back
	 */
	public function testGetRelationships()
	{
		$user = $this->model->get(1);

		$this->assertInstanceOf("\\Cactus\\Relationship\\HasMany", $user->role);
	}

	/**
	 * Testing a Model with eager loading
	 */
	public function testEagerLoading()
	{
		$user = $this->model->get(1);

		$this->assertInstanceOf("\\Cactus\\Relationship\\HasMany", $user->role);
		$this->assertInstanceOf("\\Cactus\\Tests\\UserRole", $user->role[0]);
	}

	/**
	 * Error when tyring to update new entity.
	 *
	 * @expectedException \Cactus\Exception
	 */
	public function testErrorOnUpdatingNew()
	{
		$user = new \Cactus\Tests\User(array());
		$this->model->update($user);
	}

	/**
	 * Error when trying to create an existing entity.
	 *
	 * @expectedException \Cactus\Exception
	 */
	public function testErrorOnCreateExisting()
	{
		$user = $this->model->get(1);
		$this->model->create($user);
	}

	/**
	 * Makes sure an error is thrown on the wrong entity type for a mapper.
	 *
	 * @expectedException \Cactus\Exception
	 */
	public function testErrorWrongEntityType()
	{
		$role = new \Cactus\Tests\UserRole(array());
		$this->model->save($role);
	}
}