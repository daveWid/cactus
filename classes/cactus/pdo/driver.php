<?php

namespace Cactus\PDO;

use PDO;

/**
 * The driver for PDO.
 *
 * @see        http://us3.php.net/manual/en/class.pdo.php
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Driver extends \Cactus\Driver
{
	/**
	 * @var   PDO     The PDO object used in the queries
	 */
	protected static $pdo = null;

	/**
	 * @var  string   The identifier quote character
	 */
	protected static $quote_char = "";

	/**
	 * Getter/Setter for the PDO object.
	 *
	 * @param  PDO $pdo  The pdo object to set
	 * @return PDO       The current pdo object on get
	 */
	public static function pdo(PDO $pdo = null)
	{
		if ($pdo === null)
		{
			return static::$pdo;
		}

		static::$pdo = $pdo;

		/**
		 * Sets the default identfier quote character.
		 *
		 * @see   https://github.com/j4mie/idiorm
		 */
		$char = '`'; // Backtick is the default
		$double = array("pgsql",'sqlsrv','dblib','mssql','sybase');

		if (in_array($pdo->getAttribute(PDO::ATTR_DRIVER_NAME), $double))
		{
			$char = '"';
		}

		static::$quote_char = $char;
	}

	/**
	 * Returns a row from the table with the given id for the primary key column
	 *
	 * @param   int   $id   The primary id value
	 * @return  DataMapper\Object
	 */
	public function get($id)
	{
		$sql = "SELECT * ".
			"FROM {$this->quote_identifier($this->table)} ".
			"WHERE {$this->quote_identifier($this->primary_key)} = ? ".
			"LIMIT 1";

		$params = array(
			$id
		);

		$result = $this->run($sql, $params, true);

		if (count($result) == 0)
		{
			return null;
		}

		$result = $this->process_result($result);
		return $result->current();
	}

	/**
	 * Finds records with the given parameters.
	 *
	 * @param   array   $params   The database parameters to search on
	 * @return  array             An array of DataMapper\Object items
	 */
	public function find($params = array())
	{
		$where = array();
		$additional = array();

		// Loop through the params, all keys that aren't in the column list are tacked on to the query
		foreach ($params as $key => $value)
		{
			if (in_array($key, $this->columns))
			{
				if ( ! is_array($value))
				{
					$value = array($value, "=");
				}

				list($value, $op) = $value;
				$where["{$this->quote_identifier($key)} {$op} ?"] = $value;
			}
			else
			{
				$additional[] = "{$key} $value";
			}
		}

		$sql = "SELECT * FROM {$this->quote_identifier($this->table)}";
		if ( ! empty($where))
		{
			$sql .= " WHERE ".implode(" AND ", array_keys($where));
		}

		if ( ! empty($additional))
		{
			$sql .= " ".implode(" ", $additional);
		}

		$result = $this->run($sql, array_values($where), true);
		return $this->process_result($result);
	}

	/**
	 * Creates a new record in the database.
	 *
	 * @throws  \Cactus\Exception           The passed in object is not the correct type
	 * @param   \Cactus\Entity   $object    The object to create
	 * @return  mixed                       array [insert_id, affected rows] OR boolean false for failed validation
	 */
	public function create(\Cactus\Entity & $object, $validate = true)
	{
		// Make sure it is a new object
		if ($object->is_new() !== true)
		{
			throw new \Cactus\Exception("Use \Cactus\Driver::update to save existing objects");
		}

		$this->check_object($object);

		// Check for validation and run it
		if ($validate AND ! $object->validate())
		{
			return false;
		}

		$data = $this->filter($object->data());

		$num = count($data);
		$q = implode(",", array_fill(0, $num, "?"));
		$keys = array_map(array($this, "quote_identifier"), array_keys($data));
		$sql = "INSERT INTO {$this->quote_identifier($this->table)} (".implode(",", $keys).") VALUES ({$q})";

		$affected = $this->run($sql, array_values($data));

		$id = (int) static::$pdo->lastInsertId();

		$object = $this->get($id);
		return array($id, $affected);
	}

	/**
	 * Updates a record
	 *
	 * @throws  \Cactus\Exception           The passed in object is not the correct type
	 * @param   \Cactus\Object   $object    The object to create
	 * @param   boolean          $validate  Validate the object before saving?
	 * @return  mixed                       (int) affected rows OR boolean false for failed validation
	 */
	public function update(\Cactus\Entity & $object, $validate = true)
	{
		// Make sure it is not a new object
		if ($object->is_new() === true)
		{
			throw new \Cactus\Exception("Use \Cactus\Driver::create to save a new object");
		}

		$this->check_object($object);

		// Check for validation and run it
		if ($validate AND ! $object->validate())
		{
			return false;
		}

		$data = $this->filter($object->modified());
		$affected = 0;

		if ( ! empty($data))
		{
			$params = array();
			$set = array();

			foreach ($data as $key => $value)
			{
				$set[] = "{$this->quote_identifier($key)} = ?";
				$params[] = $value;
			}

			// Tack on the primary key
			$params[] = $object->get($this->primary_key);

			$sql = "UPDATE {$this->quote_identifier($this->table)}".
				" SET ".implode(",", $set).
				" WHERE {$this->quote_identifier($this->primary_key)} = ? LIMIT 1";

			$affected = $this->run($sql, $params);
		}

		$object->clean();
		return $affected;
	}

	/**
	 * Deletes a record from the database
	 *
	 * @throws  \Cactus\Exception           The passed in object is not the correct type
	 * @param   \Cactus\Objecct   $object   The database object to delete
	 * @return  int                         The number of affected rows 
	 */
	public function delete(\Cactus\Entity & $object)
	{
		$this->check_object($object);

		$sql = "DELETE FROM {$this->quote_identifier($this->table)}".
				" WHERE {$this->quote_identifier($this->primary_key)} = ? LIMIT 1";
		$params = array(
			$object->get($this->primary_key)
		);

		$affected = $this->run($sql, $params);

		if ($affected === 1)
		{
			$object = null;
		}

		return $affected;
	}

	/**
	 * Gets all of the records associated in the table.
	 *
	 * @param  array  $values The values to use in the IN() statement.
	 * @param  string $table  The table to join
	 * @param  string $column The column to join on
	 * @return array
	 */
	public function join_in(array $values, $table, $column)
	{
		$q = implode(",", array_fill(0, count($values), "?"));
		$join = $this->quote_identifier($table);
		$table = $this->quote_identifier($this->table);
		$column = $this->quote_identifier($column);

		$query = "SELECT {$table}.* ".
			"FROM {$table} ".
			"LEFT JOIN {$join} ON {$join}.{$column} = {$table}.{$column} ".
			"WHERE {$table}.{$column} IN ({$q})";

		return $this->run($query, $values, true);
	}

	/**
	 * Runs a Select query.
	 *
	 * @param  string    $query    The PDOStatement to run
	 * @param  boolean   $select   Is this a select statement?
	 * @return mixed               The result set on select, (int) affected rows otherwise
	 */
	protected function run($query, $params = array(), $select = false)
	{
		$statement = static::$pdo->prepare($query);
		if ( ! $statement->execute($params))
		{
			$this->throw_error($statement);
		}

		return ($select) ?
			$statement->fetchAll(PDO::FETCH_CLASS, $this->object_class) :
			$statement->rowCount();
	}

	/**
	 * Quotes identifiers in the SQL statement.
	 *
	 * @param  string $name  The identifier to quote
	 * @return string        The quoted string
	 */
	protected function quote_identifier($name)
	{
		$q = self::$quote_char;
		$explode = explode('.', $name);

		return $q.implode("{$q}.{$q}", $explode).$q;
	}

	/**
	 * Throws an error when there was a problem with the query
	 *
	 * @throws \Cactus\Exeception
	 * @param  \PDOStatement $statement The PDOStatement that failed
	 */
	protected function throw_error(\PDOStatement $statement)
	{
		$error = $statement->errorInfo();
		throw new \Cactus\Exception($error[2], $error[1]);
	}

}
