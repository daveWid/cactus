<?php

namespace Cactus\Tests;

class ModelUserEager extends \Cactus\Model
{
	/**
	 * Model setup. 
	 */
	public function __construct()
	{
		parent::__construct(array(
			'table' => "user",
			'primary_key' => "user_id",
			'columns' => array(
				'user_id' => \Cactus\Field::VARCHAR,
				'email' => \Cactus\Field::VARCHAR,
				'password' => \Cactus\Field::VARCHAR,
				'last_name' => \Cactus\Field::VARCHAR,
				'first_name' => \Cactus\Field::VARCHAR,
				'status' => \Cactus\Field::INT,
				'create_date' => \Cactus\Field::DATETIME,
			),
			'object_class' => "\\Cactus\\Tests\\User",
			'relationships' => array(
				// Roles
				'role' => array(
					'type' => \Cactus\Relationship::HAS_MANY,
					'loading' => \Cactus\Loading::EAGER,
					'driver' => "\\Cactus\\Tests\\ModelUserRole",
					'column' => 'user_id'
				)
			),
		));
	}
}