<?php defined('SYSPATH') or die('No direct script access.');

abstract class DAL
{
	/**
	 * @var   string   The name of the table
	 */
	abstract public static $table;

	/**
	 * @var   string   The name of the primary key column
	 */
	abstract public static $primary_key;

	/**
	 * @var   string   The name of the doa object to return in operations
	 */
	abstract public static $object_class;

	/**
	 * Returns a row from the table with the given id for the primary key column
	 *
	 * @param   int   $id   The primary id value
	 * @return  DAO
	 */
	public static function get($id)
	{
		$result = DB::select()
			->from(static::$table)
			->where(static::$primary_key, '=', $id)
			->limit(1)
			->as_object(static::$object_class)
			->execute();

		return (count($result) == 1) ? $result->current() : null;
	}

	/**
	 * Finds records with the given parameters.
	 *
	 * @param   array   $params   The database parameters to search on
	 * @return  array             An array of DOA items
	 */
	public static function find($params = array())
	{
		
	}

	/**
	 * Saves an object.
	 *
	 * @param   DAO      $object     The object to save
	 * @param   boolean  $validate   Should the data be validated first??
	 * @return  mixed                create: [insert id, affected rows] (array)
	 *                               update: affected rows (int)
	 */
	public static function save(DAO $object, $validate = true)
	{
		return ($object->is_new) ? $this->create($object) : $this->update($object);
	}

	/**
	 * Creates a new record in the database.
	 *
	 * @param   DAO   $object    The object to create
	 * @return  array            Array of [insert id, affected rows]
	 */
	public static function create(DAO $object)
	{
		return DB::insert(static::$table)
			->columns($object->columns())
			->value($object->data())
			->execute();
	}

	/**
	 * Updates a record
	 *
	 * @param   DAO   $object   The database object to write to the database
	 * @return  int             The number of affected rows
	 */
	public static function update(DAO $object)
	{
		return DB::update(static::$table)
			->set($object->dirty())
			->where(static::$primary_key, '=', $object->{static::$primary_key})
			->limit(1)
			->execute();
	}

	/**
	 * Deletes a record from the database
	 *
	 * @param   DAO   $object   The database object to delete
	 * @return  int             The number of affected rows 
	 */
	public static function delete(DAO $object)
	{
		return DB::delete(static::$table)
			->where(static::$primary_key, '=', $object->{static::$primary_key})
			->limit(1)
			->execute();
	}

}
