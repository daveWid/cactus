<?php

namespace Cactus\Tests;

require 'PHPUnit/Extensions/Database/TestCase.php';

/**
 * The base class to load up a database connection for testing.
 *
 * @package    Cactus
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class DatabaseTest extends \PHPUnit_Extensions_Database_TestCase
{
	/**
	 * Setup the PDO adapter
	 */
	public function setUp()
	{
		$adapter = new \Cactus\Adapter\PDO($this->getConnection()->getConnection());
		\Cactus\Model::set_adapter($adapter);

		parent::setUp();
	}

	/**
	 * Gets the database connection.
	 *
	 * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
	 */
	protected function getConnection()
	{
		$pdo = new \PDO($_ENV['DB_DSN'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
		return $this->createDefaultDBConnection($pdo, $_ENV['DB_NAME']);
	}

	/**
	 * Gets the Data Set
	 *
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	protected function getDataSet()
	{
		$path = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."..").DIRECTORY_SEPARATOR."seed.xml";
		return $this->createMySQLXMLDataSet($path);
	}
}
