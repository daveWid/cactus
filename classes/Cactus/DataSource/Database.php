<?php

namespace Cactus\DataSource;

/**
 * The DataSource for Database connections
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class Database
{
	/**
	 * Runs a query to find data in the dataset.
	 *
	 * @param  string   $query     The query to run.
	 * @param  boolean $as_object  Return the result back as objects?
	 * @return mixed               The result set
	 */
	public function select($query, $as_object = null)
	{
		
	}

	/**
	 * Runs a query that will add data to the dataset
	 *
	 * @param   string $query  The query to run.
	 * @return  array          array($insert_id, $affected_rows);
	 */
	public function insert($query)
	{
		
	}

	/**
	 * Runs a query that will update data
	 *
	 * @param  string $query  The query to run
	 * @return int            The number of affected rows
	 */
	public function update($query)
	{
		
	}

	/**
	 * Runs a query that will remove data.
	 *
	 * @param  string $query  The query to run
	 * @return int            The number of deleted rows 
	 */
	public function delete($query)
	{
		
	}

}