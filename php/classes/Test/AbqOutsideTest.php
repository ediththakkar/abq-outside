<?php

namespace Edu\Cnm\AbqOutside\Test;

//use PHPUnit\Framework\TestCase;
//use PHPUnit\DbUnit\TestCaseTrait;
//use PHPUnit\DbUnit\DataSet\QueryDataSet;
//use PHPUnit\DbUnit\Database\Connection;
//use PHPUnit\DbUnit\Operation\{Composite, Factory, Operation};

// grab the encrypted properties file
//require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// autoload Composer packages
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");

/**
 * Abstract class containing universal and project specific mySQL parameters
 *
 * This class is designed to lay the foundation of the unit tests per project. It loads the all the database
 * parameters about the project so that table specific tests can share the parameters in on place. To use it:
 *
 * 1. Rename the class from DataDesignTest to a project specific name (e.g., ProjectNameTest)
 * 2. Rename the namespace to be the same as (1) (e.g., Edu\Cnm\ProjectName\Test)
 * 3. Modify DataDesignTest::getDataSet() to include all the tables in your project.
 * 4. Modify DataDesignTest::getConnection() to include the correct mySQL properties file.
 * 5. Have all table specific tests include this class.
 *
 * *NOTE*: Tables must be added in the order they were created in step (2).
 *
 * @author Gus Liakos
 **/
abstract class AbqOutsideTest extends TestCase {

	use TestCaseTrait;

	/**
	 * PHPUnit database connection interface
	 * @var Connection $connection
	 **/
	protected $connection = null;

	/**
	 * assembles the table from the schema and provides it to PHPUnit
	 *
	 * @return QueryDataSet assembled schema for PHPUnit
	 **/
	public final function getDataSet() {
		$dataset = new QueryDataSet($this->getConnection());
//tables in correct order
		$dataset->addTable("profile");
		$dataset->addTable("trail");
		$dataset->addTable("comment");
return($dataset);
}

/**
* templates the setUp method that runs before each test; this method expunges the database before each run
*
* @see https://phpunit.de/manual/current/en/fixtures.html#fixtures.more-setup-than-teardown PHPUnit Fixtures: setUp and tearDown
* @see https://github.com/sebastianbergmann/dbunit/issues/37 TRUNCATE fails on tables which have foreign key constraints
* @return Composite array containing delete and insert commands
**/
public final function getSetUpOperation() {
return new Composite([
Factory::DELETE_ALL(),
Factory::INSERT()
]);
}

/**
* templates the tearDown method that runs after each test; this method expunges the database after each run
*
* @return Operation delete command for the database
**/
public final function getTearDownOperation() {
return(Factory::DELETE_ALL());
}

public final function getConnection() {
if($this->connection === null) {
// connect to mySQL and provide the interface to PHPUnit
$config = readConfig("/etc/apache2/capstone-mysql/outside.ini");
$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/outside.ini");
$this->connection = $this->createDefaultDBConnection($pdo, $config["database"]);
}
return($this->connection);
}

/**
* returns the actual PDO object;
*
* @return \PDO active PDO object
**/
public final function getPDO() {
return($this->getConnection()->getConnection());
}
}