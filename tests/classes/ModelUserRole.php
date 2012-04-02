<?php

namespace Cactus\Tests;

class ModelUserRole extends \Cactus\Model
{
	/**
	 * Model setup 
	 */
	public function __construct()
	{
		parent::__construct(array(
			'table' => "user_role",
			'primary_key' => "user_id",
			'columns' => array(
				'user_id' => \Cactus\Field::INT,
				'role_id' => \Cactus\Field::INT
			),
			'object_class' => "\\Cactus\\Tests\\UserRole",
		));
	}
}