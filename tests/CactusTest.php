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

		$this->assertEquals('Testy', $user->first_name);
	}

	/**
	 * See if we can create a new row
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

		// Check the save
		$this->assertNotSame(false, $model->save($user)); // False on save error
		$this->assertInstanceOf("\\Cactus\\Tests\\User", $user);
	}
}