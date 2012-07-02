<?php

/**
 * A mocked up Entity class.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class MockEntity extends \Cactus\Entity
{
	/**
	 * U no can haz password!
	 *
	 * @return boolean  false
	 */
	public function getPassword()
	{
		return false;
	}

	/**
	 * Making sure the "Date" is a php date.
	 *
	 * @param  mixed $date  The date to set
	 */
	public function setCreateDate($date)
	{
		if (is_string($date))
		{
			$date = DateTime::createFromFormat("Y-m-d H:i:s", $date);
		}

		$this->data['createDate'] = $date;
		$this->modified_data['createDate'] = $date;
	}
}
