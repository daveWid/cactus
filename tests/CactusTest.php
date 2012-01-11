<?php

namespace Cactus\Tests;

/**
 * The base class to load up a database connection for testing.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class CactusTest extends \Cactus\Tests\DatabaseTest
{
	/**
	 * Setup the PDO driver
	 */
	public function setUp()
	{
		\Cactus\PDO\Driver::pdo($this->getConnection()->getConnection());
		parent::setUp();
	}

	/**
	 * See if we can read a row
	 */
	public function testRead()
	{
		$model = new \Cactus\Tests\ModelUser;
		$user = $model->get(1);

		$this->assertInstanceOf("\\Cactus\\Tests\\User", $user, "Checking for correct entity class.");
		$this->assertSame('Testy', $user->first_name, "Reading first name correctly.");
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

		$model = new \Cactus\Tests\ModelUser;
		$user = new \Cactus\Tests\User($data);

		$this->assertNotSame(false, $model->save($user), "Saving correctly, false would be an error");
	}

	/**
	 * A test to make sure the modified data is correctly being set
	 */
	public function testModified()
	{
		$new = array(
			'first_name' => "George",
			'last_name' => "Washington"
		);

		$model = new \Cactus\Tests\ModelUser;
		$user = $model->get(1);

		$user->set($new);

		$this->assertSame($new, $user->modified(), "Modified data is correct.");
	}

	/**
	 * Runs through the read, update, save procedure
	 */
	public function testUpdate()
	{
		$model = new \Cactus\Tests\ModelUser;
		$user = $model->get(1);

		$user->first_name = "Abe";
		$user->last_name = "Lincoln";

		$this->assertSame(1, $model->save($user), "One affected row on update.");
		$this->assertSame("Abe", $user->first_name, "Confirm data was updated correctly.");
	}

	/**
	 * Deletes (and nulls) an Entity
	 */
	public function testDelete()
	{
		$model = new \Cactus\Tests\ModelUser;
		$user = $model->get(1);

		$this->assertSame(1, $model->delete($user), "One affected row on delete.");
		$this->assertNull($user, "Row has been set to null");
	}

	/**
	 * Error when tyring to update new entity.
	 *
	 * @expectedException \Cactus\Exception
	 */
	public function testErrorOnUpdatingNew()
	{
		$model = new \Cactus\Tests\ModelUser;
		$user = $model->get(1);

		$model->create($user);
	}

	/**
	 * Error when trying to create an existing entity.
	 *
	 * @expectedException \Cactus\Exception
	 */
	public function testErrorOnCreateExisting()
	{
		$model = new \Cactus\Tests\ModelUser;
		$user = new \Cactus\Tests\User(array());

		$model->update($user);
	}

	/**
	 * Makes sure an error is thrown on the wrong entity type for a mapper.
	 *
	 * @expectedException \Cactus\Exception
	 */
	public function testErrorWrongEntityType()
	{
		$model = new \Cactus\Tests\ModelUser;
		$role = new \Cactus\Tests\UserRole(array());

		$model->save($role, "Error: wrong entity type.");
	}
}