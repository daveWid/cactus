<?php

namespace Cactus\Tests;

class ModelUserEager extends \Cactus\PDO\Driver
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
		'user_id' => \Cactus\FIeldType::VARCHAR,
		'email' => \Cactus\FIeldType::VARCHAR,
		'password' => \Cactus\FIeldType::VARCHAR,
		'last_name' => \Cactus\FIeldType::VARCHAR,
		'first_name' => \Cactus\FIeldType::VARCHAR,
		'status' => \Cactus\FIeldType::INT,
		'create_date' => \Cactus\FIeldType::DATETIME,
	);

	/**
	 * @var   string   The name of the object to return in operations
	 */
	protected $object_class = "\\Cactus\\Tests\\User";

	protected $relationships = array(
		// Roles
		'role' => array(
			'type' => \Cactus\Relationship::HAS_MANY,
			'loading' => \Cactus\Loading::EAGER,
			'driver' => "\\Cactus\\Tests\\ModelUserRole",
			'column' => 'user_id'
		)
	);
}