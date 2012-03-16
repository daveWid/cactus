<?php

namespace Cactus\Adapter;

/**
 * A Database adapter for PDO.
 *
 * @see        http://us3.php.net/manual/en/class.pdo.php
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class PDO implements \Cactus\Adapter
{
	/**
	 * @var \PDO  The PDO connection object
	 */
	private $pdo;
	
	/**
	 * Run a SELECT query.
	 *
	 * @param  string $query      The query to run
	 * @param  string $as_object  Return the result as an object?
	 * @return mixed              The complete database result set
	 */
	public function select($query, $as_object = null)
	{
		$statement = $this->execute($query);

		$result = ($as_object !== null) ?
			$statement->fetchAll(\PDO::FETCH_CLASS, $as_object) :
			$statement->fetchAll();

		return $result;
	}

	/**
	 * Run an INSERT query.
	 *
	 * @param  string $query  The SQL query
	 * @return array          array($insert_id, $number) 
	 */
	public function insert($query)
	{		
		$statement = $this->execute($query);
		return array($this->pdo->lastInsertId() , $statement->rowCount());
	}

	/**
	 * Run an UPDATE query.
	 *
	 * @param  string $query  The SQL query
	 * @return int            Affected rows
	 */
	public function update($query)
	{
		$statement = $this->execute($query);
		return $statement->rowCount();
	}

	/**
	 * Run a DELETE query.
	 *
	 * @param  string $query  The SQL query to run
	 * @return int            The number of deleted rows 
	 */
	public function delete($query)
	{
		$statement = $this->execute($query);
		return $statement->rowCount();
	}

	/**
	 * Executes a pre-built query. Throws a \Cactus\Exception if something goes
	 * wrong with the query.
	 *
	 * @throws \Cactus\Exception
	 *
	 * @param  string $query  SQL query
	 * @return \PDOStatement
	 */
	private function execute($query)
	{
		try{
			$statement = $this->pdo->query($query);
		} catch(\PDOException $e) {
			throw new \Cactus\Exception($e->getMessage());
		}

		return $statement;
	}

	/**
	 * Gets the connection object.
	 *
	 * @param  mixed $db  The database connection object used internally
	 * @return mixed      [get] The connection object OR [set] $this
	 */
	public function connection($db = null)
	{
		if ($db === null)
		{
			return $this->pdo;
		}

		$this->pdo = $db;
		return $this;
	}

}
