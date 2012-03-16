<?php

namespace Cactus\Tests;

class ModelUserHasOne extends \Cactus\Driver\Base
{
	/**
	 * @var   string   The name of the table
	 */
	protected $table = "user";

	/**
	 * @var   string   The name of the primary key column
	 */
	protected $primary_key = "user_id";

	/**
	 * @var   array    The list of columns in the table
	 */
	protected $columns = array(
		'user_id' => \Cactus\Field::VARCHAR,
		'email' => \Cactus\Field::VARCHAR,
		'password' => \Cactus\Field::VARCHAR,
		'last_name' => \Cactus\Field::VARCHAR,
		'first_name' => \Cactus\Field::VARCHAR,
		'status' => \Cactus\Field::INT,
		'create_date' => \Cactus\Field::DATETIME,
	);

	/**
	 * @var   string   The name of the object to return in operations
	 */
	protected $object_class = "\\Cactus\\Tests\\User";

	protected $relationships = array(
		// Roles
		'role' => array(
			'type' => \Cactus\Relationship::HAS_ONE,
			'driver' => "\\Cactus\\Tests\\ModelUserRole",
			'column' => 'user_id'
		)
	);
}