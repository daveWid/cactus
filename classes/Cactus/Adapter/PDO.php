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
	 * @param  string $query   The query to run.
	 * @param  array  $data    An array of data to bind to the query
	 * @param  array  $params  A list of parameters to bind to the query
	 * @return array           The result set from the query
	 */
	public function select($query, array $params = array())
	{
		list($statement) = $this->run($query, $params);
		return $statement->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * Runs a query that will add data to the dataset
	 *
	 * @param  string $query  The query to run.
	 * @param  array  $params A list of parameters to bind to the query
	 * @return array          array($insert_id, $affected_rows);
	 */
	public function insert($query, array $params = array())
	{
		list($statement) = $this->run($query, $params);
		return array((int) $this->connection->lastInsertId(), $statement->rowCount());
	}

	/**
	 * Runs a query that will update data
	 *
	 * @param  string $query  The query to run
	 * @param  array  $params A list of parameters to bind to the query
	 * @return int            The number of affected rows
	 */
	public function update($query, array $params = array())
	{
		list($statement) = $this->run($query, $params);
		return $statement->rowCount();
	}

	/**
	 * Runs a query that will remove data.
	 *
	 * @param  string $query  The query to run
	 * @param  array  $params A list of parameters to bind to the query
	 * @return int            The number of deleted rows 
	 */
	public function delete($query, array $params = array())
	{
		list($statement) = $this->run($query, $params);
		return $statement->rowCount();
	}

	/**
	 * Runs a raw query.
	 *
	 * @param  string $query The query
	 * @return boolean       Success
	 */
	public function query($query)
	{
		list($statement, $success) = $this->run($query, array());
		return $success;
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
	 * @return array          [\PDOStatement, (boolean) success]
	 */
	private function run($query, array $params)
	{
		try{
			$this->queries[] = $query;

			$statement = $this->connection->prepare($query);
			$success = $statement->execute($params);
		} catch(\PDOException $e) {
			throw new \Cactus\Exception($e->getMessage());
		}

		return array($statement, $success);
	}

}
