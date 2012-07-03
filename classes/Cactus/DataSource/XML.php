<?php

namespace Cactus\DataSource;

/**
 * An XML DataSource.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class XML implements \Cactus\DataSource
{
	/**
	 * @var SimpleXML  The parsed xml file
	 */
	private $xml = null;

	/**
	 * Loads the dataset if a path is passed in.
	 *
	 * @param string $path  The path to the datasource to load.
	 */
	public function __construct($path = null)
	{
		if ($path !== null)
		{
			$this->load($path);
		}
	}

	/**
	 * Loads up a dataset from the given path.
	 *
	 * @param string $path  The location of the xml data set
	 */
	public function load($path)
	{
		$this->xml = simplexml_load_file($path);
	}

	/**
	 * Runs a query to find data in the dataset.
	 *
	 * @param  string  $query      The query to run.
	 * @param  array   $data       An array of data to bind to the query
	 * @param  boolean $as_object  Return the result back as objects?
	 * @return \Cactus\ResultSet   The result set from the query
	 */
	public function select($query, $data = array(), $as_object = null)
	{
		$result = $this->xml->xpath($query);
		if ($result === false)
		{
			return false;
		}

		$rs = new \Cactus\ResultSet;
		foreach ($result as $row)
		{
			if ($as_object === null)
			{
				$rs->add((array) $row);
			}
			else
			{
				$object = new $as_object;
				foreach ($row as $key => $value)
				{
					$object->{$key} = (string) $value;
				}

				if ($object instanceof \Cactus\Entity)
				{
					$object->clean();
				}

				$rs->add($object);
			}
		}

		return $rs;
	}

	/**
	 * Runs a query that will add data to the dataset
	 *
	 * @param   string $query  The query to run.
	 * @param   array  $data   An array of data to bind to the query
	 * @return  array          array($insert_id, $affected_rows);
	 */
	public function insert($query, $data = array())
	{
		throw new \Cactus\Exception(get_called_class()."::insert has not be implemented yet");
	}

	/**
	 * Runs a query that will update data
	 *
	 * @param  string $query  The query to run
	 * @param  array  $data   An array of data to bind to the query
	 * @return int            The number of affected rows
	 */
	public function update($query, $data = array())
	{
		throw new \Cactus\Exception(get_called_class()."::update has not be implemented yet");
	}

	/**
	 * Runs a query that will remove data.
	 *
	 * @param  string $query  The query to run
	 * @param  array  $data   An array of data to bind to the query
	 * @return int            The number of deleted rows 
	 */
	public function delete($query, $data = array())
	{
		throw new \Cactus\Exception(get_called_class()."::delete has not be implemented yet");
	}

}
