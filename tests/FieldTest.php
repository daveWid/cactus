<?php

namespace Cactus\Tests;

/**
 * Sanity check on the field conversions.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class FieldTest extends \Cactus\Tests\DatabaseTest
{
	/**
	 * @var \Cactus\Tests\User
	 */
	public $row;

	/**
	 * Setup the PDO adapter
	 */
	public function setUp()
	{
		$adapter = new \Cactus\Adapter\PDO;
		$adapter->set_connection($this->getConnection()->getConnection());

		$model = new \Cactus\Tests\ModelUser;
		$model->set_adapter($adapter);

		$this->row = $model->get(1);

		parent::setUp();
	}

	/**
	 * @dataProvider get_converts
	 */
	public function testConvert($field, $test, $value)
	{
		$this->{$test}($value, $this->row->{$field});
	}

	/**
	 * Gets a list of fields to check conversion for. 
	 */
	public function get_converts()
	{
		return array(
			array('first_name', 'assertInternalType', 'string'),
			array('status', 'assertInternalType', 'int'),
			array('create_date', 'assertInstanceOf', 'DateTime'),
		);
	}
}