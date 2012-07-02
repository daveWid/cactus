<?php

namespace Cactus\DataSource;

/**
 * An XML DataSource.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class XML
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
	 * @param  string   $query     The query to run.
	 * @param  boolean $as_object  Return the result back as objects?
	 * @return mixed               The result set
	 */
	public function select($query, $as_object = null)
	{
		$result = $this->xml->xpath($query);
		if ($result === false)
		{
			return false;
		}

		$data = array();
		foreach ($result as $row)
		{
			if ($as_object === null)
			{
				$data[] = (array) $row;
			}
			else
			{
				$object = new $as_object;
				foreach ($row as $key => $value)
				{
					$object->{$key} = (string) $value;
				}

				$data[] = $object;
			}
		}

		return $data;
	}

}
