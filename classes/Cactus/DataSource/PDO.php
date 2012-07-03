<?php

namespace Cactus\DataSource;

/**
 * The DataSource that uses a PDO connection.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class PDO
{
	/**
	 * @var \PDO  The PDO connection object
	 */
	private $connection = null;

	/**
	 * Creates a new PDO DataSource with the connection information passed in.
	 *
	 * @param \PDO $connection  The PDO connection information.
	 */
	public function __construct(\PDO $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * Runs a query to find data in the dataset.
	 *
	 * @param  string  $query      The query to run.
	 * @param  array   $data       An array of data to bind to the query
	 * @param  boolean $as_object  Return the result back as objects?
	 * @return mixed               The result set
	 */
	public function select($query, $data = array(), $as_object = null)
	{
		
	}

	/**
	 * Runs a query that will add data to the dataset
	 *
	 * @param   string $query  The query to run.
	 * @param   array  $data   An array of data to bind to the query
	 * @return  array          array($insert_id, $affected_rows);
	 */
	public function insert($query, $data = array())
	{
		
	}

	/**
	 * Runs a query that will update data
	 *
	 * @param  string $query  The query to run
	 * @param  array  $data   An array of data to bind to the query
	 * @return int            The number of affected rows
	 */
	public function update($query, $data = array())
	{
		
	}

	/**
	 * Runs a query that will remove data.
	 *
	 * @param  string $query  The query to run
	 * @param  array  $data   An array of data to bind to the query
	 * @return int            The number of deleted rows 
	 */
	public function delete($query, $data = array())
	{
		
	}

}