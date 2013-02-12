<?php

class MigrationSetup_001 extends \Cactus\Migration
{
	public function up()
	{
		$query = new \Peyote\Create('migration_user');
		$query->setColumns(array(
			new \Peyote\Column('user_id', 'serial'),
			new \Peyote\Column('email', 'varchar', array('length' => 100, 'is_null' => false)),
			new \Peyote\Column('password', 'varchar', array('length' => 64, 'is_null' => false))
		));

		return $this->adapter->query($query->compile());
	}

	public function down()
	{
		$query = new \Peyote\Drop('migration_user');
		return $this->adapter->query($query->compile());
	}

}
