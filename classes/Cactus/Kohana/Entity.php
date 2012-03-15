<?php

namespace Cactus\Kohana;

/**
 * A Kohana based Entity.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class Entity extends \Cactus\Entity
{
	/**
	 * Checks to see if the data is valid
	 *
	 * @uses     \Validation::check
	 * @return   boolean   Does this object contain valid data?
	 */
	public function validate()
	{
		if ($this->validation === null)
		{
			$this->validation = $this->validation_rules(new \Validation($this->data));
		}

		return $this->validation->check();
	}

	/**
	 * Gets any validation errors
	 *
	 * @uses    \Validation::errors
	 * @param   type     $file        The path to the message file
	 * @param   boolean  $translate   Translate the errors?
	 * @return  array
	 */
	public function errors($file = null, $translate = true)
	{
		return $this->validation->errors($file, $translate);
	}

}
