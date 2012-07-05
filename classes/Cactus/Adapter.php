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
	 * @param  string  $query      The query to run.
	 * @param  array   $data       An array of data to bind to the query
	 * @return array               The result set from the query
	 */
	public function select($query);

	/**
	 * Runs a query that will add data to the dataset
	 *
	 * @param   string $query  The query to run.
	 * @return  array          array($insert_id, $affected_rows);
	 */
	public function insert($query);

	/**
	 * Runs a query that will update data
	 *
	 * @param  string $query  The query to run
	 * @return int            The number of affected rows
	 */
	public function update($query);

	/**
	 * Runs a query that will remove data.
	 *
	 * @param  string $query  The query to run
	 * @return int            The number of deleted rows 
	 */
	public function delete($query);

}
