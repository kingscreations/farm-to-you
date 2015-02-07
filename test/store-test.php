<?php

// first, require SimpleTest framework <http://www.simpletest.org/>
// this path is *NOT* universal, but deployed on the bootcamp-coders server
require_once ("/usr/lib/php5/simpletest/autorun.php");

// next, require the class from the project under scrutiny
require_once ("../php/classes/user.php");
require_once ("../php/classes/profile.php");
require_once ("../php/classes/store.php");

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
/**
 * Unit test for the Store class
 *
 * This is a SimpleTest test case for the CRUD methods of the Store class.
 *
 * @see Store
 * @author Alonso Indacochea <alonso@hermesdevelopment.com>
 **/
class StoreTest extends UnitTestCase {
	/**
	 * mysqli object shared amongst all tests
	 **/
	private $mysqli = null;
	/**
	 * instance of the objects we are testing with
	 **/
	private $user = null;
	private $user2 = null;
	private $profile = null;
	private $profile2 = null;
	private $store = null;
	private $store2 = null;

	// this section contains member variables with constants needed for creating a new store
	/**
	 * date the Store was created
	 **/
	private $creationDate = null;
	/**
	 * name of the test Store
	 **/
	private $storeName = "Pass Farms";
	/**
	 * path of the Store image
	 **/
	private $imagePath = "http://www.google.com";

	/**
	 * sets up the mySQL connection for this test
	 **/
	public function setUp() {
		// first, connect to mysqli
		mysqli_report(MYSQLI_REPORT_STRICT);
		$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
		$configArray = readConfig($configFile);
		$this->mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"], $configArray["database"]);

		// second, create an instance of the objects under scrutiny
		$this->creationDate = new DateTime();
		$this->user = new User(null, "test@test.com", 'AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB0BC99AB10BC99AC99AB0BC99AB10BC99AB10BC99AB1010', '99AB10BC99AB10BC99AB10BC99AB10BC', '99AB10BC99AB10BC');
		$this->user->insert($this->mysqli);
		$this->profile = new Profile(null, "Test", "Test2", "5555555555", "m", "012345", "http://www.cats.com/cat.jpg", $this->user->getUserId());
		$this->profile->insert($this->mysqli);
		$this->store = new Store(null, $this->profile->getProfileId(), $this->storeName, $this->imagePath, $this->creationDate);
	}

	/**
	 * tears down the connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		// destroy the objects if they were created
		if($this->store !== null && $this->store->getStoreId() !== null) {
			$this->store->delete($this->mysqli);
			$this->store = null;
		}
		if($this->store2 !== null && $this->store2->getStoreId() !== null) {
			$this->store2->delete($this->mysqli);
			$this->store2 = null;
		}
		if($this->profile !== null && $this->profile->getProfileId() !== null) {
			$this->profile->delete($this->mysqli);
			$this->profile = null;
		}
		if($this->profile2 !== null && $this->profile2->getProfileId() !== null) {
			$this->profile2->delete($this->mysqli);
			$this->profile2 = null;
		}
		if($this->user !== null && $this->user->getUserId() !== null) {
			$this->user->delete($this->mysqli);
			$this->user = null;
		}
		if($this->user2 !== null && $this->user2->getUserId() !== null) {
			$this->user2->delete($this->mysqli);
			$this->user2 = null;
		}

		// disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}

	/**
	 * test inserting a valid Store into mySQL
	 **/
	public function testInsertValidStore() {
		// zeroth, ensure the Store and mySQL class are sane
		$this->assertNotNull($this->store);
		$this->assertNotNull($this->mysqli);

		// first, insert the Store into mySQL
		$this->store->insert($this->mysqli);

		// second, grab a Store from mySQL
		$mysqlStore = Store::getStoreByStoreId($this->mysqli, $this->store->getStoreId());

		// third, assert the Store we have created and mySQL's Store are the same object
		$this->assertIdentical($this->store->getStoreId(), $mysqlStore->getStoreId());
		$this->assertIdentical($this->store->getProfileId(), $mysqlStore->getProfileId());
		$this->assertIdentical($this->store->getCreationDate(), $mysqlStore->getCreationDate());
		$this->assertIdentical($this->store->getStoreName(), $mysqlStore->getStoreName());
		$this->assertIdentical($this->store->getImagePath(), $mysqlStore->getImagePath());

	}

	/**
	 * test inserting an invalid Store into mySQL
	 **/
	public function testInsertInvalidStore() {
		// zeroth, ensure the Store and mySQL class are sane
		$this->assertNotNull($this->store);
		$this->assertNotNull($this->mysqli);

		// first, set the Store id to an invented value that should never insert in the first place
		$this->store->setStoreId(1042);

		// second, try to insert the Store and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->store->insert($this->mysqli);

		// third, set the Store to null to prevent tearDown() from deleting a Store that never existed
		$this->store = null;
	}

	/**
	 * test deleting a Store from mySQL
	 **/
	public function testDeleteValidStore() {
		// zeroth, ensure the Store and mySQL class are sane
		$this->assertNotNull($this->store);
		$this->assertNotNull($this->mysqli);

		// first, assert the Store is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->store->insert($this->mysqli);
		$mysqlStore = Store::getStoreByStoreId($this->mysqli, $this->store->getStoreId());
		$this->assertIdentical($this->store->getStoreId(), $mysqlStore->getStoreId());

		// second, delete the Store from mySQL and re-grab it from mySQL and assert it does not exist
		$this->store->delete($this->mysqli);
		$mysqlStore = Store::getStoreByStoreId($this->mysqli, $this->store->getStoreId());
		$this->assertNull($mysqlStore);

		// third, set the Store to null to prevent tearDown() from deleting a Store that has already been deleted
		$this->store = null;
	}

	/**
	 * test deleting a Store from mySQL that does not exist
	 **/
	public function testDeleteInvalidStore() {
		// zeroth, ensure the Store and mySQL class are sane
		$this->assertNotNull($this->store);
		$this->assertNotNull($this->mysqli);

		// first, try to delete the Store before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->store->delete($this->mysqli);

		// second, set the Store to null to prevent tearDown() from deleting a Store that has already been deleted
		$this->store = null;
	}

	/**
	 * test updating a Store from mySQL
	 **/
	public function testUpdateValidStore() {
		// zeroth, ensure the Store and mySQL class are sane
		$this->assertNotNull($this->store);
		$this->assertNotNull($this->mysqli);

		// first, assert the Store is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->store->insert($this->mysqli);
		$mysqlStore = Store::getStoreByStoreId($this->mysqli, $this->store->getStoreId());
		$this->assertIdentical($this->store->getStoreId(), $mysqlStore->getStoreId());

		// second, change the Store, update it mySQL
		$newContent = "Updated Farms";
		$this->store->setStoreName($newContent);
		$this->store->update($this->mysqli);

		// third, re-grab the Store from mySQL
		$mysqlStore = Store::getStoreByStoreId($this->mysqli, $this->store->getStoreId());
		$this->assertNotNull($mysqlStore);

		// fourth, assert the Store we have updated and mySQL's Store are the same object
		$this->assertIdentical($this->store->getStoreId(), $mysqlStore->getStoreId());
		$this->assertIdentical($this->store->getProfileId(), $mysqlStore->getProfileId());
		$this->assertIdentical($this->store->getCreationDate(), $mysqlStore->getCreationDate());
		$this->assertIdentical($this->store->getStoreName(), $mysqlStore->getStoreName());
		$this->assertIdentical($this->store->getImagePath(), $mysqlStore->getImagePath());
	}

	/**
	 * test updating a Store from mySQL that does not exist
	 **/
	public function testUpdateInvalidStore() {
		// zeroth, ensure the Store and mySQL class are sane
		$this->assertNotNull($this->store);
		$this->assertNotNull($this->mysqli);

		// first, try to update the Store before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->store->update($this->mysqli);

		// second, set the Store to null to prevent tearDown() from deleting a Store that has already been deleted
		$this->store = null;
	}
	/**
	 * test getStoreByValidStoreName by inserting two identical Stores into mySQL, calling them with getStoreByStoreName,
	 * and asserting mySQL Stores and original Stores are identical
	 **/
	public function testGetStoreByValidStoreName() {
		// zeroth, create second Location
		$this->user2 = new User(null, "test@test.com", 'AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB0BC99AB10BC99AC99AB0BC99AB10BC99AB10BC99AB1010', '99AB10BC99AB10BC99AB10BC99AB10BC', '99AB10BC99AB10BC');
		$this->user2->insert($this->mysqli);
		$this->profile2 = new Profile(null, "Test", "Test2", "5555555555", "m", "012345", "http://www.cats.com/cat.jpg", $this->user2->getUserId());
		$this->profile2->insert($this->mysqli);
		$this->store2 = new Store(null, $this->profile2->getProfileId(), $this->storeName, $this->imagePath, $this->creationDate);

		// zeroth #2, ensure the Stores and mySQL class are sane
		$this->assertNotNull($this->store);
		$this->assertNotNull($this->store2);
		$this->assertNotNull($this->mysqli);

		// first, insert the Stores into mySQL
		$this->store->insert($this->mysqli);
		$this->store2->insert($this->mysqli);
		// second, grab the Locations from mySQL
		$mysqlStores = Store::getStoreByStoreName($this->mysqli, $this->storeName);

		// third, assert the Stores we have created and mySQL's Stores are the same object
		foreach($mysqlStores as $mysqlStore) {
			$this->assertNotNull($mysqlStore->getStoreId());
			$this->assertTrue($mysqlStore->getStoreId() > 0);
			$this->assertIdentical($this->store->getStoreName(), $mysqlStore->getStoreName());
			$this->assertIdentical($this->store2->getStoreName(), $mysqlStore->getStoreName());
		}
	}
	/**
	 * test getStoreByInvalidStoreName by inserting two identical Stores into mySQL, searching for a different store
	 * name, and asserting that the result is null
	 **/
	public function testGetStoreByInvalidStoreName() {
		// zeroth, create second Store
		$this->user2 = new User(null, "test@test.com", 'AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB0BC99AB10BC99AC99AB0BC99AB10BC99AB10BC99AB1010', '99AB10BC99AB10BC99AB10BC99AB10BC', '99AB10BC99AB10BC');
		$this->user2->insert($this->mysqli);
		$this->profile2 = new Profile(null, "Test", "Test2", "5555555555", "m", "012345", "http://www.cats.com/cat.jpg", $this->user2->getUserId());
		$this->profile2->insert($this->mysqli);
		$this->store2 = new Store(null, $this->profile2->getProfileId(), $this->storeName, $this->imagePath, $this->creationDate);

		// zeroth #2, ensure the Stores and mySQL class are sane
		$this->assertNotNull($this->store);
		$this->assertNotNull($this->store2);
		$this->assertNotNull($this->mysqli);

		// first, insert the Stores into mySQL
		$this->store->insert($this->mysqli);
		$this->store2->insert($this->mysqli);

		// second, create an array grabbing Stores that doesn't exist
		$mysqlStores = Store::getStoreByStoreName($this->mysqli, "Test Farms");

		// third, assert results array is null
		$this->assertNull($mysqlStores);
	}

}
?>