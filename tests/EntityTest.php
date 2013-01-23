<?php

/**
 * Testing the Entity class
 *
 * @package   Cactus
 * @author    Dave Widmer <dave@davewidmer.net>
 */
class EntityTest extends PHPUnit_Framework_TestCase
{
	public $entity;

	public $data = array(
		'name' => "Dave"
	);

	public function setUp()
	{
		$this->entity = new \Cactus\Entity($this->data);
	}

	public function testNewEntity()
	{
		$this->assertTrue($this->entity->isNew());
	}

	public function testGetData()
	{
		$this->assertSame("Dave", $this->entity->name);
	}

	public function testGetAsArray()
	{
		$this->assertSame($this->data, $this->entity->asArray());
	}

	/**
     * @expectedException PHPUnit_Framework_Error_Notice
     */
	public function testGetUndefinedPropertyTriggersNotice()
	{
		$this->entity->fail;
	}

	public function testSetData()
	{
		$college = "BGSU";

		$this->entity->college = $college;
		$this->assertSame($college, $this->entity->college);
	}

	public function testFill()
	{
		$data = array(
			'college' => "BGSU",
			'major'   => "VCT"
		);

		$this->entity->fill($data);

		$this->assertSame($data['college'], $this->entity->college);
		$this->assertSame($data['major'], $this->entity->major);
	}

	public function testGetAsArrayWithKeys()
	{
		$data = array(
			'college' => "BGSU"
		);

		$this->entity->fill($data);

		$this->assertSame($data, $this->entity->asArray(array('college')));
	}

	public function testGetModifiedData()
	{
		$this->assertSame($this->data, $this->entity->getModifiedData());
	}

	public function testResetModifiedData()
	{
		$this->entity->reset();
		$this->assertSame(array(), $this->entity->getModifiedData());
	}

	public function testUsingSameDataDoesntModify()
	{
		$this->entity->reset();
		$this->entity->name = "Dave";
		$this->assertSame(array(), $this->entity->getModifiedData());
	}

	public function testDataTypeModificationIsInternal()
	{
		// Please do this in a factory or in the mapper...
		$this->entity->dataStructure = array(
			'name' => false,
			'create_date' => 'dateTime'
		);

		$this->entity->create_date = date("Y-m-d H:i:s");
		$this->assertInstanceOf('DateTime', $this->entity->create_date);
	}

}
