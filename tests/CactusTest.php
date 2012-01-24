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
	 * Test to see if null is returned when a row isn't found
	 */
	public function testNonExsitsIsNull()
	{
		$model = new \Cactus\Tests\ModelUser;
		$user = $model->get(10);

		$this->assertNull($user, "Null on no user found");
	}

	/**
	 * Test to see if we can access the data using array notation.
	 */
	public function testArrayAccess()
	{
		$model = new \Cactus\Tests\ModelUser;
		$user = $model->get(1);

		$this->assertSame("Testy", $user['first_name'], "Using ArrayAccess to get value.");
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
	 * Tests a call for all records
	 */
	public function testAll()
	{
		$model = new \Cactus\Tests\ModelUser;
		$users = $model->all();

		$this->assertNotSame(0, count($users), "Fetching all records.");
	}

	/**
	 * A sample test on the finder.
	 */
	public function testFind()
	{
		$model = new \Cactus\Tests\ModelUser;
		$users = $model->find(array(
			'first_name' => "Abe",
			'ORDER BY' => "`user_id` DESC",
			'LIMIT' => 1
		));

		$this->assertNotSame(0, count($users), "Finding records.");
	}

	/**
	 * Testing a working relationship
	 */
	public function testIterateRelationship()
	{
		$model = new \Cactus\Tests\ModelUser;
		$user = $model->get(1);

		// There are 2 roles for the 1st user
		$this->assertSame(2, count($user->role), "User has 2 roles");

		foreach ($user->role as $role)
		{
			$this->assertInstanceOf("\\Cactus\\Tests\\UserRole", $role);
		}
	}

	/**
	 * Get the relationships array back
	 */
	public function testGetRelationships()
	{
		$model = new \Cactus\Tests\ModelUser;
		$user = $model->get(1);

		// There are 2 roles for the 1st user
		$this->assertInstanceOf("\\Cactus\\PDO\\Driver", $user->role->driver());
	}

	/**
	 * Testing a model with no relationships
	 */
	public function testNoRelationships()
	{
		$model = new \Cactus\Tests\ModelRole;
		$role = $model->get(1); // not really needed, just want to test the add_relationships function.

		$this->assertEmpty($model->relationships(), "Testing no relationships");
	}

	/**
	 * Testing a Model with eager loading
	 */
	public function testHasOne()
	{
		$model = new \Cactus\Tests\ModelUserHasOne;
		$user = $model->get(1);

		$this->assertInstanceOf("\\Cactus\\Relationship\\HasOne", $user->role);
	}

	/**
	 * Testing a Model with eager loading
	 */
	public function testEagerLoading()
	{
		$model = new \Cactus\Tests\ModelUserEager;
		$user = $model->get(1);

		$this->assertInstanceOf("\\Cactus\\Relationship\\HasMany", $user->role);
		$this->assertInstanceOf("\\Cactus\\Tests\\UserRole", $user->role[0]);
	}

	public function testFailedValidation()
	{
		
	}

	public function testDriverErrorOnBadQuery()
	{
		
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