<?php

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
		$pdo = new PDO("mysql:host=localhost;dbname={$_ENV['DB_NAME']}", $_ENV['DB_USER'], $_ENV['DB_PASS']);
		return $this->createDefaultDBConnection($pdo, $_ENV['DB_NAME']);
	}

	/**
	 * Gets the Data Set
	 *
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	protected function getDataSet()
	{
		$path = dirname(__DIR__).DIRECTORY_SEPARATOR."seed.xml";
		return $this->createMySQLXMLDataSet($path);
	}
}
