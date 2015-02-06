<?php
// first, require the SimpleTest framework <http://www.simpletest.org/>
// this path is *NOT* universal, but deployed on the bootcamp-coders server
require_once("/usr/lib/php5/simpletest/autorun.php");

// next, require the class from the project under scrutiny
require_once("../php/classes/user.php");

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

/**
 * unit test for the User class
 *
 * This is a simpletest test case for the CRUD methods of the User class
 *
 * @see user
 * @author Jason King <jason@kingscreations.org>
 *
 **/

class UserTest extends UnitTestCase {
	/**
	 * mysqli object shared amongst all tests
	 **/
	private $mysqli = null;
	/**
	 * instance of the object we are testing with
	 **/
	private $user = null;

	// this section contains member variables with constants needed for creating a new user
	/**
	 * user id of the person who is inserting the test User
	 * @deprecated a parent class of type Profile should be used here instead
	 **/
	/**
	 * email of the test user
	 **/
	private $email = "BillyJoBob@suspender.com";
	/**
	 * hash generated from test users awesome password
	 **/
	private $hash = "12345678123456781234567812345678123456781234567812345678123456781234567812345678123456781234567812345678123456781234567812345678";
	/**
	 * salt to add to hash of test user
	 **/
	private $salt = "48121620481216204812162048121620";
	/**
	 * activation value for test user
	 **/
	private $activation = "1234567812345678";
			/**
	 * sets up the mySQL connection for this test
	 **/
	public function setUp() {
		// first, connect to mysqli
		mysqli_report(MYSQLI_REPORT_STRICT);
		$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
		$this->mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

		// second, create an instance of the object under scrutiny
		$this->user = new User(null, $this->email, $this->hash, $this->salt, $this->activation);
	}

	/**
	 * tears down the connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		// destroy the object if it was created
		if($this->user !== null) {
			$this->user->delete($this->mysqli);
			$this->user = null;
		}

		// disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}

	/**
	 * test inserting a valid User into mySQL
	 **/
	public function testInsertValidUser() {
		// zeroth, ensure the User and mySQL class are sane
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->mysqli);

		// first, insert the User into mySQL
		$this->user->insert($this->mysqli);

		// second, grab a User from mySQL
		$mysqlUser = User::getUserByUserId($this->mysqli, $this->user->getUserId());

		// third, assert the User we have created and mySQL's User are the same object
		$this->assertIdentical($this->user->getUserId(), $mysqlUser->getUserId());
		$this->assertIdentical($this->user->getEmail(), $mysqlUser->getEmail());
		$this->assertIdentical($this->user->getHash(), $mysqlUser->getHash());
		$this->assertIdentical($this->user->getSalt(), $mysqlUser->getSalt());
		$this->assertIdentical($this->user->getActivation(), $mysqlUser->getActivation());

	}

	/**
	 * test inserting an invalid User into mySQL
	 **/
	public function testInsertInvalidUser() {
		// zeroth, ensure the User and mySQL class are sane
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->mysqli);

		// first, set the user id to an invented value that should never insert in the first place
		$this->user->setUserId(42);

		// second, try to insert the User and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->user->insert($this->mysqli);

		// third, set the User to null to prevent tearDown() from deleting a User that never existed
		$this->user = null;
	}

	/**
	 * test deleting a User from mySQL
	 **/
	public function testDeleteValidUser() {
		// zeroth, ensure the User and mySQL class are sane
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->mysqli);

		// first, assert the User is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->user->insert($this->mysqli);
		$mysqlUser = User::getUserByUserId($this->mysqli, $this->user->getUserId());
		$this->assertIdentical($this->user->getUserId(), $mysqlUser->getUserId());

		// second, delete the User from mySQL and re-grab it from mySQL and assert it does not exist
		$this->user->delete($this->mysqli);
		$mysqlUser = User::getUserByUserId($this->mysqli, $this->user->getUserId());
		$this->assertNull($mysqlUser);

		// third, set the User to null to prevent tearDown() from deleting a User that has already been deleted
		$this->user = null;
	}

	/**
	 * test deleting a User from mySQL that does not exist
	 **/
	public function testDeleteInvalidUser() {
		// zeroth, ensure the User and mySQL class are sane
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->mysqli);

		// first, try to delete the User before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->user->delete($this->mysqli);

		// second, set the User to null to prevent tearDown() from deleting a User that has already been deleted
		$this->user = null;
	}

	/**
	 * test updating a User from mySQL
	 **/
	public function testUpdateValidUser() {
		// zeroth, ensure the User and mySQL class are sane
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->mysqli);

		// first, assert the User is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->user->insert($this->mysqli);
		$mysqlUser = User::getUserByUserId($this->mysqli, $this->user->getUserId());
		$this->assertIdentical($this->user->getUserId(), $mysqlUser->getUserId());

		// second, change the User, update it mySQL
		$newEmail = "chuckieCheese@rocks.com";
		$this->user->setEmail($newEmail);
		$this->user->update($this->mysqli);

		// third, re-grab the User from mySQL
		$mysqlUser = User::getUserByUserId($this->mysqli, $this->user->getUserId());
		$this->assertNotNull($mysqlUser);

		// fourth, assert the User we have updated and mySQL's User are the same object
		$this->assertIdentical($this->user->getUserId(), $mysqlUser->getUserId());
		$this->assertIdentical($this->user->getEmail(), $mysqlUser->getEmail());
		$this->assertIdentical($this->user->getHash(), $mysqlUser->getHash());
		$this->assertIdentical($this->user->getSalt(), $mysqlUser->getSalt());
		$this->assertIdentical($this->user->getActivation(), $mysqlUser->getActivation());
	}

	/**
	 * test updating a User from mySQL that does not exist
	 **/
	public function testUpdateInvalidUser() {
		// zeroth, ensure the User and mySQL class are sane
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->mysqli);

		// first, try to update the User before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->user->update($this->mysqli);

		// second, set the User to null to prevent tearDown() from deleting a User that has already been deleted
		$this->user = null;
	}
	/**
	 *test getting a valid user by userId
	 **/
	public function testGetValidUserByUserId() {
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->mysqli);

		$this->user->insert($this->mysqli);
		$mysqlUser = User::getUserByUserId($this->mysqli, $this->user->getUserId());
		$this->assertIdentical($this->user->getUserId(), $mysqlUser->getUserId());
	}

	/**
	 * test getting a valid user by using an invalid userId
	 **/
	public function testGetInvalidUserByUserId() {
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->mysqli);

		$this->user->insert($this->mysqli);
		$mysqlUser = User::getUserByUserId($this->mysqli, 99);
		$this->assertNull($mysqlUser);
	}
	/**
	 *test getting a valid user by email
	 **/
	public function testGetValidUserByEmail() {
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->mysqli);

		$this->user->insert($this->mysqli);
		$mysqlUser = User::getUserByEmail($this->mysqli, $this->user->getEmail());
		$this->assertIdentical($this->user->getEmail(), $mysqlUser->getEmail());
	}

	/**
	 * test getting a valid user by using an invalid email
	 **/
	public function testGetInvalidUserByEmail() {
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->mysqli);

		$this->user->insert($this->mysqli);
		$mysqlUser = User::getUserByEmail($this->mysqli, "ImaBlackHat@hacker.com");
		$this->assertNull($mysqlUser);
	}
	/**
	 *test getting a valid user by activation code
	 **/
	public function testGetValidUserByActivation() {
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->mysqli);

		$this->user->insert($this->mysqli);
		$mysqlUser = User::getUserByActivation($this->mysqli, $this->user->getActivation());
		$this->assertIdentical($this->user->getActivation(), $mysqlUser->getActivation());
	}

	/**
	 * test getting a valid user by using an invalid activation code
	 **/
	public function testGetInvalidUserByActivation() {
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->mysqli);

		$this->user->insert($this->mysqli);
		$mysqlUser = User::getUserByActivation($this->mysqli, 9234567812345678);
		$this->assertNull($mysqlUser);
	}
}
?>