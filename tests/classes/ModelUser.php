<?php

namespace Cactus\Tests;

class ModelUser extends \Cactus\PDO\Driver
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
	protected $columns = array('user_id','email','password','last_name','first_name','status','create_date');

	/**
	 * @var   string   The name of the object to return in operations
	 */
	protected $object_class = "\\Cactus\\Tests\\User";

	protected $relationships = array(
		// Roles
		'role' => array(
			'type' => \Cactus\Relationship::HAS_ONE,
			//'loading' => \Cactus\Loading::EAGER,
			'driver' => "\\Cactus\\Tests\\ModelUserRole",
			'column' => 'user_id'
		)
	);
}