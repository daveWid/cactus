<?php

class UserSeed extends \Cactus\Seed
{
	public function run()
	{
		$mapper = new UserMapper($this->adapter);

		$user = new \Cactus\Entity(array(
			'name' => 'Mr. Test',
			'password' => "testingpassword",
			'create_date' => date("Y-m-d H:i:s")
		));

		list($id, $num) = $mapper->save($user);

		// Dont do this for real, but I need to clean up for testing
		$mapper->delete($user);

		return $num === 1;
	}
}
