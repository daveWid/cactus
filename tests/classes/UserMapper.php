<?php

/**
 * A Mapper for the test user table. Using MySQL yo!
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class UserMapper extends \Cactus\Mapper\MySQL
{
	/**
	 * Setting the identity for the mapper. 
	 */
	protected function init()
	{
		$this->table = "user";
		$this->primary_key = "user_id";

		$this->columns = array(
			'user_id' => 'integer',
			'name' => false,
			'password' => false,
			'create_date' => 'dateTime'
		);
	}
}
