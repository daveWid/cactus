<?php

namespace Cactus;

/**
 * The interface for Cactus drivers.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
interface Driver
{
	/**
	 * Getter/Setter for the database adapter that is used to run the queries.
	 *
	 * @param  \Cactus\Adapter $adapter  The adapter used to execute sql querier
	 * @return mixed                    Adapter [get] OR $this [set]
	 */
	public function adapter(\Cactus\Adapter $adapter = null);

	/**
	 * Returns a row from the table with the given id for the primary key column
	 *
	 * @param   int   $id   The primary id value
	 * @return  \Cactus\Entity
	 */
	public function get($id);

	/**
	 * Gets all of the rows in the database.
	 *
	 * @param   string   $column      The column to order on
	 * @param   string   $direction   The directory to sort
	 * @return  array                 An array of DataMapper\Object items
	 */
	public function all($column = null, $direction = 'DESC');

	/**
	 * Finds records with the given parameters.
	 *
	 * @param   array   $params   The database parameters to search on
	 * @return  array             An array of \Cactus\Entity items
	 */
	public function find($params = array());

	/**
	 * Saves an object.
	 *
	 * @param   \Cactus\Entity   $object     The object to save
	 * @param   boolean          $validate   Should the data be validated first??
	 * @return  mixed                        \Cactus\Entity OR boolean false for failed validation
	 */
	public function save(\Cactus\Entity & $object, $validate = true);

	/**
	 * Creates a new record in the database.
	 *
	 * @throws  \Cactus\Exception         The passed in object is not the correct type
	 * @param   \Cactus\Object   $object  The object to create
	 * @return  mixed                     array [insert_id, affected rows] OR boolean false for failed validation
	 */
	public function create(\Cactus\Entity & $object, $validate = true);

	/**
	 * Updates a record
	 *
	 * @throws  \Cactus\Exception           The passed in object is not the correct type
	 * @param   \Cactus\Entity   $object    The object to create
	 * @param   boolean          $validate  Validate the object before saving?
	 * @return  mixed                       (int) affected rows OR boolean false for failed validation
	 */
	public function update(\Cactus\Entity & $object, $validate = true);

	/**
	 * Deletes a record from the database
	 *
	 * @throws  \Cactus\Exception          The passed in object is not the correct type
	 * @param   \Cactus\Entity   $object   The database object to delete
	 * @return  int                        The number of affected rows
	 */
	public function delete(\Cactus\Entity & $object);

	/**
	 * Gets all of the records associated in the table.
	 *
	 * @param  array  $values  The values to join in
	 * @param  string $table   The table to join
	 * @param  string $column  The column to join on
	 * @return array
	 */
	public function join_in(array $values, $table, $column);

	/**
	 * Gets all of the relationships for the DataMapper
	 *
	 * @return  array  List of relationship
	 */
	public function relationships();

	/**
	 * Adds a relationship to a result.
	 *
	 * @param  \Cactus\Entity $entity  The Cactus object to add relationships to
	 * @param  array          $data    If an eager load, the eager load data.
	 * @return \Cactus\Entity
	 */
	public function add_relationship(\Cactus\Entity $result, array $data = array());

	/**
	 * Checks to make sure the object passed in is of the correct type.
	 *
	 * @throws  \Cactus\Exception           The passed in object is not the correct type
	 * @param   \Cactus\Entity   $object    The datamapper object to check
	 */
	public function check_object(\Cactus\Entity $object);

}
