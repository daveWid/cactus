<?php
namespace DataMapper;

/**
 * The interface for DataMapper drivers.
 *
 * @package    DataMapper
 * @author     Dave Widmer <dave@davewidmer.net>
 */
interface DriverInterface
{
	/**
	 * Returns a row from the table with the given id for the primary key column
	 *
	 * @param   int   $id   The primary id value
	 * @return  DataMapper\Object
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
	 * @return  array             An array of DataMapper\Object items
	 */
	public function find($params = array());

	/**
	 * Saves an object.
	 *
	 * @param   DataMapper\Object   $object     The object to save
	 * @param   boolean             $validate   Should the data be validated first??
	 * @return  mixed                           DataMapper\Object OR boolean false for failed validation
	 */
	public function save(\Datamapper\Object & $object, $validate = true);

	/**
	 * Creates a new record in the database.
	 *
	 * @throws  Datamapper_Exception           The passed in object is not the correct type
	 * @param   DataMapper_Object   $object    The object to create
	 * @return  mixed                          array [insert_id, affected rows] OR boolean false for failed validation
	 */
	public function create(\Datamapper\Object & $object, $validate = true);

	/**
	 * Updates a record
	 *
	 * @throws  DataMapper\Exception           The passed in object is not the correct type
	 * @param   DataMapper\Object   $object    The object to create
	 * @param   boolean             $validate  Validate the object before saving?
	 * @return  mixed                          (int) affected rows OR boolean false for failed validation
	 */
	public function update(\DataMapper\Object & $object, $validate = true);

	/**
	 * Deletes a record from the database
	 *
	 * @throws  DataMapper\Exception           The passed in object is not the correct type
	 * @param   DataMapper\Objecct   $object   The database object to delete
	 * @return  int                            The number of affected rows
	 */
	public function delete(\DataMapper\Object & $object);

	/**
	 * Gets all of the relationships for the DataMapper
	 *
	 * @return  array  List of relationship
	 */
	public function relationships();

	/**
	 * Adds a relationship to a result.
	 *
	 * @param   DataMapper\Object   $result   The DataMapper object to add relationships to
	 * @return  DataMapper\Object
	 */
	public function add_relationship(\DataMapper\Object $result);

	/**
	 * Checks to make sure the object passed in is of the correct type.
	 *
	 * @throws  DataMapper_Exception           The passed in object is not the correct type
	 * @param   DataMapper_Object   $object    The datamapper object to check
	 */
	public function check_object(\DataMapper\Object $object);

}
