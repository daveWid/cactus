<?php

namespace Cactus\Tests;

class ModelRole extends \Cactus\PDO\Driver
{
	/**
	 * @var   string   The name of the table
	 */
	protected $table = "role";

	/**
	 * @var   string   The name of the primary key column
	 */
	protected $primary_key = "role_id";

	/**
	 * @var   array    The list of columns in the table
	 */
	protected $columns = array(
		'role_id' => \Cactus\FieldType::INT,
		'name' => \Cactus\FieldType::VARCHAR,
	);

	/**
	 * @var   string   The name of the object to return in operations
	 */
	protected $object_class = "\\Cactus\\Tests\\Role";

}