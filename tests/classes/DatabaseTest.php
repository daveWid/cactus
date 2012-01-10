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
	 * Gets the database connection.
	 *
	 * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
	 */
	protected function getConnection()
	{
		$pdo = new \PDO(MYSQL_DSN, MYSQL_USER, MYSQL_PASSWORD);
		return $this->createDefaultDBConnection($pdo, DB_NAME);
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
