<?php

namespace Cactus\Tests;

class ModelRole extends \Cactus\Model
{
	/**
	 * Model setup.
	 */
	public function __construct()
	{
		parent::__construct(array(
			'table' => "role",
			'primary_key' => "role_id",
			'object_class' => "\\Cactus\\Tests\\Role",
			'columns' => array(
				'role_id' => \Cactus\Field::INT,
				'name' => \Cactus\Field::VARCHAR,
			),
		));
	}

}