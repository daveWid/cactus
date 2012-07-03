<?php

/**
 * Some testing on the entity classes.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class MockUserTest extends PHPUnit_Framework_TestCase
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

		$this->entity = new MockUser;

		// Faking entity creation for testing purposes...
		$this->entity->setArray(array(
			'name' => 'Dave',
			'passowrd' => 'hidden',
			'createDate' => "2012-07-03 11:01:26"
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

	public function testOverriddenSetChangesModifiedData()
	{
		$date = "2012-07-02 13:02:14";
		$this->entity->createDate = $date;

		$modified = $this->entity->getModifiedData();
		$this->assertSame($modified['createDate'], $this->entity->createDate);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error_Notice
	 */
	public function testNonExistingProperty()
	{
		var_export($this->entity->fail);
	}

}