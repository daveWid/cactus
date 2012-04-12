<?php

namespace Cactus\Tests;

/**
 * Some testing on the entity classes.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class EntityTest extends \Cactus\Tests\DatabaseTest
{
	/**
	 * @var \Cactus\Tests\ModelUser  The model to interact with the data
	 */
	public $model;

	/**
	 * @var \Cactus\Tests\User  The test user row
	 */
	public $user;

	/**
	 * Setup the PDO adapter
	 */
	public function setUp()
	{
		parent::setUp();

		$this->model = new \Cactus\Tests\ModelUser;
		$this->user = $this->model->get(1);
	}

	/**
	 * Testing the creation of a "new" entity. 
	 */
	public function testIsNew()
	{
		// Existing row
		$this->assertFalse($this->user->is_new());

		// Now create a "new" User
		$user = new \Cactus\Tests\User(array(
			'first_name' => "Testy",
			'last_name' => "TestMaker"
		));

		$this->assertTrue($user->is_new());
	}

	/**
	 * Test the different ways to get access to the data. 
	 */
	public function testDataAccess()
	{
		$this->assertSame($this->user->first_name, $this->user['first_name']);
	}

	/**
	 * A test to make sure the modified data is being tracked correctly and then
	 * cleaned when asked
	 */
	public function testModifiedAndClean()
	{
		$new = array(
			'first_name' => "George",
			'last_name' => "Washington"
		);

		$this->user->set($new);
		$this->assertSame($new, $this->user->modified());

		$this->user->clean();
		$this->assertEmpty($this->user->modified());
	}
}