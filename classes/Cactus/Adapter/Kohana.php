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
	 */
	public function __construct()
	{
		$this->db = \Database::instance();
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

	/**
	 * Get/Set the database connection object.
	 *
	 * @param  mixed $db  The database object.
	 * @return mixed      The database object [get] OR $this [set]
	 */
	public function get_connection($db = null)
	{
		return $this->db;
	}

	/**
	 * Set the database connection for the adapter.
	 *
	 * @param  \Database $db   The database class to use when running queries.
	 * @return \Cactus\Adapter\Kohana
	 */
	public function set_connection($db)
	{
		$this->db = $db;
		return $this;
	}

}
