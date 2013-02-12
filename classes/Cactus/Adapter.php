<?php

namespace Cactus;

/**
 * The interface that data source adapters must follow.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
interface Adapter
{
	/**
	 * Runs a query to find data in the dataset.
	 *
	 * @param  string $query   The query to run.
	 * @param  array  $data    An array of data to bind to the query
	 * @param  array  $params  A list of parameters to bind to the query
	 * @return array           The result set from the query
	 */
	public function select($query, array $params = array());

	/**
	 * Runs a query that will add data to the dataset
	 *
	 * @param  string $query  The query to run.
	 * @param  array  $params A list of parameters to bind to the query
	 * @return array          array($insert_id, $affected_rows);
	 */
	public function insert($query, array $params = array());

	/**
	 * Runs a query that will update data
	 *
	 * @param  string $query  The query to run
	 * @param  array  $params A list of parameters to bind to the query
	 * @return int            The number of affected rows
	 */
	public function update($query, array $params = array());

	/**
	 * Runs a query that will remove data.
	 *
	 * @param  string $query  The query to run
	 * @param  array  $params A list of parameters to bind to the query
	 * @return int            The number of deleted rows 
	 */
	public function delete($query, array $params = array());

	/**
	 * Runs a raw query.
	 *
	 * @param  string $query The query
	 * @return boolean       Success
	 */
	public function query($query);

	/**
	 * Gets a list of all of the queries that have been run.
	 *
	 * @return array
	 */
	public function getQueries();

}
