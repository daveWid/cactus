<?php

class MigrationUserMapper extends \Cactus\Mapper\MySQL
{
	/**
	 * Setting the identity for the mapper. 
	 */
	protected function init()
	{
		$this->table = "migration_user";
		$this->primary_key = "user_id";

		$this->columns = array(
			'user_id' => 'integer',
			'name' => false,
			'password' => false
		);
	}
}