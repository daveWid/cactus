<?php
namespace DataMapper\Relationship;

/**
 * A HasOne relationship for DataMapper.
 *
 * @package    DataMapper
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class HasOne extends \DataMapper\Relationship
{
	/**
	 * Gets the result set.
	 */
	public function result()
	{
		if ( ! $this->has_result)
		{
			$this->has_result = true;

			$this->result = $this->mapper->find(array(
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
		$data = $this->result();
		return isset($data[$name]) ? $data[$name] : null;
	}

}
