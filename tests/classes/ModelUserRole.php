<?php

namespace Cactus\Tests;

class ModelUserRole extends \Cactus\Kohana\Driver
{
	/**
	 * @var   string   The name of the table
	 */
	protected $table = "user_role";

	/**
	 * @var   string   The name of the primary key column
	 */
	protected $primary_key = "user_id";

	/**
	 * @var   array    The list of columns in the table
	 */
	protected $columns = array('user_id','role_id');

	/**
	 * @var   string   The name of the doa object to return in operations
	 */
	protected $object_class = "\\Cactus\\Tests\\UserRole";

}