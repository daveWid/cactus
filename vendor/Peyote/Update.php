<?php

namespace Peyote;

/**
 * A class that builds UPDATE statments.
 *
 * @link       http://dev.mysql.com/doc/refman/5.0/en/update.html
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Update extends \Peyote\Base
{
	/**
	 * @var array  The data to update
	 */
	private $data = array();

	/**
	 * @var \Peyote\Where  The where clause
	 */
	protected $where;

	/**
	 * @var \Peyote\OrderBy  The order by clause
	 */	
	protected $order_by;

	/**
	 * @var \Peyote\Limit  The limit clause
	 */
	protected $limit;

	/**
	 * Create a new Update instance.
	 *
	 * @param mixed $table   The table name OR array($table, $alias)
	 */
	public function __construct($table = null, \Peyote\Where $where = null)
	{
		if ($table !== null)
		{
			$this->table($table);
		}

		$this->where = ($where !== null) ? $where : new \Peyote\Where;
		$this->order_by = new \Peyote\OrderBy;
		$this->limit = new \Peyote\Limit;
	}

	/**
	 * Sets the values as column => value pairs
	 *
	 * @param  array $data  An array of column => value pairs
	 * @return $this
	 */
	public function set(array $data)
	{
		$this->data = array_merge($this->data, $data);
		return $this;
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		$sql = array("UPDATE");
		$sql[] = $this->table();
		$sql[] = "SET";

		$data = array();
		foreach ($this->data as $column => $value)
		{
			$data[] = "{$column} = {$this->quote($value)}";
		}

		$sql[] = implode(", ", $data);

		// Where?
		$where = $this->where->compile();
		if ($where !== "")
		{
			$sql[] = $where;
		}

		// Order?
		$order = $this->order_by->compile();
		if ($order !== "")
		{
			$sql[] = $order;
		}

		// Limit?
		$limit = $this->limit->compile();
		if ($limit !== "")
		{
			$sql[] = $limit;
		}

		return implode(" ", $sql);
	}

	/**
	 * Get the class properties to use as "traits".
	 *
	 * @return array
	 */
	protected function traits()
	{
		return array("where","order_by","limit");
	}

}
