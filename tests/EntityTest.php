<?php

/**
 * Some testing on the entity classes.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class EntityTest extends PHPUnit_Framework_TestCase
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

		$this->entity = new \Cactus\Entity;

		// Faking entity creation for testing purposes...
		$this->entity->setArray(array(
			'name' => 'Dave Widmer',
			'library' => 'Cactus',
			'php' => '>=5.3.0'
		));
		$this->entity->clean();
	}

	public function testNewEntity()
	{
		$entity = new \Cactus\Entity(array(
			'name' => 'Dave Widmer',
			'library' => 'Cactus',
			'php' => '>=5.3.0'
		));

		$this->assertTrue($entity->isNew());
	}

	public function testModifiedData()
	{
		$changed = array(
			'language' => "PHP 5.3+",
			'name' => "Changy McChangerson"
		);

		$this->entity->setArray($changed);

		$this->assertSame($changed, $this->entity->getModifiedData());
	}

	public function testGet()
	{
		$this->assertSame("Dave Widmer", $this->entity->name);
	}

	public function testSet()
	{
		$name = 'Changy McChangerson';
		$this->entity->name = $name;

		$this->assertSame($name, $this->entity->name);
	}

}