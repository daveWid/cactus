<?php

namespace Cactus\Adapter;

/**
 * A PDO Adapter implementation.
 *
 * This adapter will run whatever query you throw at it, so all of the data
 * validation/sanitation should be done before your run it through the adapter.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class PDO implements \Cactus\Adapter
{
	/**
	 * @var \PDO  The PDO connection object
	 */
	private $connection = null;

	/**
	 * @var array An internal list of queries run
	 */
	private $queries = array();

	/**
	 * Creates a new PDO Adapter with the connection information passed in.
	 *
	 * @param \PDO $connection  The PDO connection information.
	 */
	public function __construct(\PDO $connection)
	{
		$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$this->connection = $connection;
	}

	/**
	 * Runs a query to find data in the dataset.
	 *
	 * @param  string  $query      The query to run.
	 * @param  boolean $as_object  Return the result back as objects?
	 * @return array               The result set
	 */
	public function select($query)
	{
		$statement = $this->run($query);
		return $statement->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * Runs a query that will add data to the dataset
	 *
	 * @param   string $query  The query to run.
	 * @return  array          array($insert_id, $affected_rows);
	 */
	public function insert($query)
	{
		$statement = $this->run($query);
		return array((int) $this->connection->lastInsertId(), $statement->rowCount());
	}

	/**
	 * Runs a query that will update data
	 *
	 * @param  string $query  The query to run
	 * @return int            The number of affected rows
	 */
	public function update($query)
	{
		$statement = $this->run($query);
		return $statement->rowCount();
	}

	/**
	 * Runs a query that will remove data.
	 *
	 * @param  string $query  The query to run
	 * @return int            The number of deleted rows 
	 */
	public function delete($query)
	{
		$statement = $this->run($query);
		return $statement->rowCount();
	}

	/**
	 * Gets a list of all of the queries that have been run.
	 *
	 * @return array
	 */
	public function getQueries()
	{
		return $this->queries;
	}

	/**
	 * Runs a SQL query and handles any errors.
	 *
	 * @throws \Cactus\Exception
	 *
	 * @param  string $query  The SQL query to run
	 * @return \PDOStatement
	 */
	private function run($query)
	{
		try{
			$queries[] = $query;
			$statement = $this->connection->query($query);
		} catch(\PDOException $e) {
			throw new \Cactus\Exception($e->getMessage());
		}

		return $statement;
	}

}
