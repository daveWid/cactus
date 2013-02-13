<?php

class MigrationUserSeed extends \Cactus\Seed
{
	public function run()
	{
		$mapper = new MigrationUserMapper($this->adapter);
		$user = new \Cactus\Entity(array(
			'email' => 'fake@site.com',
			'password' => "password"
		));

		list($id, $num) = $mapper->save($user);
		return $num === 1;
	}
}
