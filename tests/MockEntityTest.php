<?php

/**
 * Some testing on the entity classes.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class MockEntityTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \Cactus\Entity  An entity used for testing.
	 */
	public $entity;

	/**
	 * Create a default entity 
	 */
	public function setUp()
	{
		parent::setUp();

		$this->entity = new MockEntity;

		// Faking entity creation for testing purposes...
		$this->entity->setArray(array(
			'name' => 'Dave Widmer',
			'library' => 'Cactus',
			'php' => '>=5.3.0',
			'password' => "hidden",
			'createDate' => "2012-07-01 12:04:57"
		));
		$this->entity->clean();
	}

	public function testOverriddenGet()
	{
		$this->assertFalse($this->entity->password);
	}

	public function testOverriddenSet()
	{
		$this->assertInstanceOf("DateTime", $this->entity->createDate);
	}
}