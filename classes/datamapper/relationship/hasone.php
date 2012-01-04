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
		if ( ! $this->_has_result)
		{
			$this->_has_result = true;

			$this->_result = $this->_mapper->find(array(
				$this->_column => $this->_value
			))->current();
		}

		return $this->_result;
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
