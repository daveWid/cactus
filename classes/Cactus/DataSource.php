<?php

namespace Cactus;

/**
 * The interface for data sources in the Cactus library
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
interface DataSource
{
	/**
	 * Runs a query to find data in the dataset.
	 *
	 * @param  string  $query      The query to run.
	 * @param  array   $data       An array of data to bind to the query
	 * @param  boolean $as_object  Return the result back as objects?
	 * @return \Cactus\ResultSet   The result set from the query
	 */
	public function select($query, $data = array(), $as_object = null);

	/**
	 * Runs a query that will add data to the dataset
	 *
	 * @param   string $query  The query to run.
	 * @param   array  $data   An array of data to bind to the query
	 * @return  array          array($insert_id, $affected_rows);
	 */
	public function insert($query, $data = array());

	/**
	 * Runs a query that will update data
	 *
	 * @param  string $query  The query to run
	 * @param  array  $data   An array of data to bind to the query
	 * @return int            The number of affected rows
	 */
	public function update($query, $data = array());

	/**
	 * Runs a query that will remove data.
	 *
	 * @param  string $query  The query to run
	 * @param  array  $data   An array of data to bind to the query
	 * @return int            The number of deleted rows 
	 */
	public function delete($query, $data = array());

}