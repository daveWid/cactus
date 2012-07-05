<?php

/**
 * Testing the Collection class
 *
 * @package   Cactus
 * @author    Dave Widmer <dave@davewidmer.net>
 */
class CollectionTest extends PHPUnit_Framework_TestCase
{
	public $collection;

	public function setUp()
	{
		parent::setUp();

		$this->collection = new \Cactus\Collection;
	}

	public function testEmptyCollection()
	{
		$this->assertSame(0, $this->collection->count());
	}

	public function testAddItemToCollection()
	{
		$this->addEntities(1);
		$this->assertSame(1, $this->collection->count());
	}

	public function testObjectAndArrayAccess()
	{
		$this->addEntities(3);
		$index = 0;

		foreach ($this->collection as $row)
		{
			$this->assertSame($row, $this->collection[$index++]);
		}
	}

	/**
	 * Adds a given number of entities to the collection.
	 *
	 * @param int $num  The number of entities to add
	 */
	private function addEntities($num)
	{
		while($num-- > 0)
		{
			$this->collection->add(new \Cactus\Entity);
		}
	}

}
