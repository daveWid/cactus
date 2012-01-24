<?php

namespace Cactus\Relationship;

/**
 * A HasOne relationship.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class HasOne extends \Cactus\Relationship
{
	/**
	 * Gets the result set.
	 */
	public function result()
	{
		if ( ! $this->has_result)
		{
			$this->has_result = true;

			$this->result = $this->driver->find(array(
				$this->column => $this->value
			))->current();
		}

		return $this->result;
	}

	/**
	 * The "magic" get method.
	 *
	 * @param   string   $name   The property name to get
	 * @return  mixed            The value or null
	 */
	public function __get($name)
	{
		$this->result();
		return isset($this->result[$name]) ? $this->result[$name] : null;
	}

}
