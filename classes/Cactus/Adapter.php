<?php

namespace Cactus;

/**
 * The interface for Database adapters that want to play nice with Cactus.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
interface Adapter
{
	/**
	 * Run a SELECT query.
	 *
	 * @param  string $query      The query to run
	 * @param  string $as_object  Return the result as an object?
	 * @return mixed              The complete database result set
	 */
	public function select($query, $as_object = null);

	/**
	 * Run an INSERT query.
	 *
	 * @param  string $query  The SQL query
	 * @return array          array($insert_id, $number) 
	 */
	public function insert($query);

	/**
	 * Run an UPDATE query.
	 *
	 * @param  string $query  The SQL query
	 * @return int            Affected rows
	 */
	public function update($query);

	/**
	 * Run a DELETE query.
	 *
	 * @param  string $query  The SQL query to run
	 * @return int            The number of deleted rows 
	 */
	public function delete($query);

}
