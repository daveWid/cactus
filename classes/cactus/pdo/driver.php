<?php
namespace DataMapper\PDO;

use PDO;

/**
 * The driver for PDO.
 *
 * @see        http://us3.php.net/manual/en/class.pdo.php
 *
 * @package    DataMapper
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Driver extends \DataMapper\Driver
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
		static::set_quote_character();
	}

	/**
	 * Return the correct character used to quote identifiers (table
	 * names, column names etc) by looking at the driver being used by PDO.
	 *
	 * This was taken from idiorm and updated...
	 *
	 * @see   https://github.com/j4mie/idiorm
	 */
	protected static function set_quote_character()
	{
		$char = '`'; // Backtick is the default
		$double = array("pgsql",'sqlsrv','dblib','mssql','sybase');

		if (in_array(static::$pdo->getAttribute(PDO::ATTR_DRIVER_NAME), $double))
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
		$sql = "SELECT * FROM ? WHERE ?";

		$params = array(
			$this->quote_identifier($this->table),
			$this->set_param($this->primary_key, $id, '=')
		);

		$sql = $this->compile_query($sql, $params);
		$result = $this->run($sql);

		if (count($result) == 0)
		{
			return null;
		}

		$result = $this->add_relationship($result[0]);
		return $result->clean();
	}

	/**
	 * Finds records with the given parameters.
	 *
	 * @param   array   $params   The database parameters to search on
	 * @return  array             An array of DataMapper\Object items
	 */
	public function find($params = array())
	{
		$sql = "SELECT * FROM ? ? ?";

		$where = array();
		$additional = array();

		// Loop through the params, all keys that aren't in the column list are converted to DB::select method calls.
		foreach ($params as $key => $value)
		{
			if (in_array($key, $this->columns))
			{
				if ( ! is_array($value))
				{
					$value = array($value, "=");
				}

				list($value, $op) = $value;
				$where[] = $this->set_param($key, $value, $op);
			}
			else
			{
				$additional[] = $key." ".$value;
			}
		}

		$params = array(
			$this->quote_identifier($this->table),
			empty($where) ? "" : "WHERE ".implode(" AND ", $where),
			empty($additional) ? "" : implode(" ", $additional)
		);

		$query = $this->compile_query($sql, $params);
		$result = $this->run($query);

		return $this->clean_result($result);
	}

	/**
	 * Creates a new record in the database.
	 *
	 * @throws  \Datamapper\Exception           The passed in object is not the correct type
	 * @param   \DataMapper\Object   $object    The object to create
	 * @return  mixed                          array [insert_id, affected rows] OR boolean false for failed validation
	 */
	public function create(\Datamapper\Object & $object, $validate = true)
	{
		// Make sure it is a new object
		if ($object->is_new() !== true)
		{
			throw new \DataMapper\Exception("Use DataMapper::update to save existing objects");
		}

		$this->check_object($object);

		// Check for validation and run it
		if ($validate AND ! $object->validate())
		{
			return false;
		}

		$data = $this->filter($object->data());

		$sql = "INSERT INTO ? (?) VALUES (?)";
		$columns = array_map(array($this, 'quote_identifier'), array_keys($data));
		$values = array_map(array($this, 'quote'), array_values($data));
		$params = array(
			$this->quote_identifier($this->table),
			implode(",", $columns),
			implode(",", $values)
		);

		$query = $this->compile_query($sql, $params);

		$pdo = static::$pdo;
		$statement = $pdo->prepare($query);
		if ( ! $statement->execute())
		{
			$this->throw_error($statement);
		}

		$id = (int) $pdo->lastInsertId();

		$object = $this->get($id);
		return array($id, 1);
	}

	/**
	 * Updates a record
	 *
	 * @throws  DataMapper\Exception           The passed in object is not the correct type
	 * @param   DataMapper\Object   $object    The object to create
	 * @param   boolean             $validate  Validate the object before saving?
	 * @return  mixed                          (int) affected rows OR boolean false for failed validation
	 */
	public function update(\DataMapper\Object & $object, $validate = true)
	{
		// Make sure it is not a new object
		if ($object->is_new() === true)
		{
			throw new \DataMapper\Exception("Use DataMapper::create to save a new object");
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
			$sql = "UPDATE ? SET ? WHERE ? LIMIT 1";

			$set = array_map(array($this, "set_param"), array_keys($data), array_values($data));
			$params = array(
				$this->quote_identifier($this->table),
				implode(",", $set),
				$this->set_param($this->primary_key, $object->get($this->primary_key), "=")
			);

			$query = $this->compile_query($sql, $params);

			$statement = static::$pdo->query($query);
			if ( ! $statement->execute())
			{
				$this->throw_error($statement);
			}

			$affected = $statement->rowCount();
		}

		$object->clean();
		return $affected;
	}

	/**
	 * Deletes a record from the database
	 *
	 * @throws  DataMapper\Exception           The passed in object is not the correct type
	 * @param   DataMapper\Objecct   $object   The database object to delete
	 * @return  int                            The number of affected rows 
	 */
	public function delete(\DataMapper\Object & $object)
	{
		$this->check_object($object);

		$sql = "DELETE FROM ? WHERE ? LIMIT 1";
		$params = array(
			$this->quote_identifier($this->table),
			$this->set_param($this->primary_key, $object->get($this->primary_key), "=")
		);

		$query = $this->compile_query($sql, $params);

		$statement = static::$pdo->query($query);

		if ( ! $statement->execute())
		{
			$this->throw_error($statement);
		}

		$object = null;
		return 1;
	}

	/**
	 * Quotes a value.
	 *
	 * @param  string $name  The identifier to quote
	 * @return string        The quoted string
	 */
	protected function quote($value)
	{
		return static::$pdo->quote($value);
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
	 * Runs a Select query.
	 *
	 * @param  \PDO   $pdo    The PDO object
	 * @param  string $query  The query to run
	 * @return array          The result set
	 */
	protected function run($query)
	{
		$sql = static::$pdo->prepare($query);
		if ( ! $sql->execute())
		{
			$this->throw_error($sql);
		}

		return $sql->fetchAll(PDO::FETCH_CLASS, $this->object_class);
	}

	/**
	 * Debugs a prepared query.
	 *
	 * @param  string $sql     The SQL statement with placeholders
	 * @param  array  $params  SQL parameters
	 * @return string          The full query
	 */
	protected function compile_query($sql, array $params = array())
	{
		return vsprintf(str_replace("?", "%s", $sql), $params);
	}

	/**
	 * Mapping function for the update function
	 *
	 * @param  string $key    The param key
	 * @param  string $value  The value
	 * @param  string $op     The operator
	 * @return string         The parameter
	 */
	protected function set_param($key, $value, $op = "=")
	{
		return $this->quote_identifier($key)." {$op} ".$this->quote($value);
	}

	/**
	 * Throws an error when there was a problem with the query
	 *
	 * @throws \DataMapper\Exeception
	 * @param \PDOStatement $statement The PDOStatement that failed
	 */
	protected function throw_error(\PDOStatement $statement)
	{
		$error = $statement->errorInfo();
		throw new \DataMapper\Exception($error[2], $error[1]);
	}

}
