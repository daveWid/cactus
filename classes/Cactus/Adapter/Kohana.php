<?php

namespace Cactus\Adapter;

/**
 * The driver for the Kohana framework.
 *
 * This class assumes that you have the Database module activated.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Kohana implements \Cactus\Adapter
{
	/**
	 * @var \Database  The datbase instance.
	 */
	private $db;

	/**
	 * Setup the default database instance.
	 *
	 * @param \Database $db  The database instance
	 */
	public function __construct(\Database $db)
	{
		$this->db = $db;
	}

	/**
	 * Run a SELECT query.
	 *
	 * @param  string $query      The query to run
	 * @param  string $as_object  Return the result as an object?
	 * @return mixed              The complete database result set
	 */
	public function select($query, $as_object = null)
	{
		if ($as_object === null)
		{
			$as_object = false;
		}

		return $this->db->query(\Database::SELECT, $query, $as_object);
	}

	/**
	 * Run an INSERT query.
	 *
	 * @param  string $query  The SQL query
	 * @return array          array($insert_id, $number) 
	 */
	public function insert($query)
	{
		return $this->db->query(\Database::INSERT, $query);
	}

	/**
	 * Run an UPDATE query.
	 *
	 * @param  string $query  The SQL query
	 * @return int            Affected rows
	 */
	public function update($query)
	{
		return $this->db->query(\Database::UPDATE, $query);
	}

	/**
	 * Run a DELETE query.
	 *
	 * @param  string $query  The SQL query to run
	 * @return int            The number of deleted rows 
	 */
	public function delete($query)
	{
		return $this->db->query(\Database::DELETE, $query);
	}

}
