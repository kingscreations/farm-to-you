<?php
// first, require the SimpleTest framework <http://www.simpletest.org/>
require_once("/usr/lib/php5/simpletest/autorun.php");
// the class to test
require_once("../php/classes/storeLocation.php");
// the classes required for foreign key access
require_once ("../php/classes/user.php");
require_once ("../php/classes/profile.php");
require_once ("../php/classes/store.php");
require_once ("../php/classes/location.php");
// require the encrypted configuration functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
/**
 * Unit test for the storeLocation class
 *
 * This is a SimpleTest test case for the CRUD methods of the storeLocation class.
 *
 * @see storeLocation
 * @author Alonso Indacochea <alonso@hermesdevelopment.com>
 **/
class StoreLocationTest extends UnitTestCase {
	/**
	 * mysqli object shared amongst all tests
	 **/
	private $mysqli = null;
	/**
	 * instance of the objects we are testing with
	 **/
	private $storeLocation = null;
	private $user = null;
	private $profile = null;
	private $store = null;
	private $location = null;
	/**
	 * sets up the mySQL connection for this test
	 **/
	public function setUp() {
		// get the credentials information from the server
		$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
		$configArray = readConfig($configFile);
		// connection
		mysqli_report(MYSQLI_REPORT_STRICT);
		$this->mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
			$configArray["database"]);
		// instance of objects under scrutiny
		$date = new DateTime();
		$this->user = new User(null, "test@test.com", 'AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB0BC99AB10BC99AC99AB0BC99AB10BC99AB10BC99AB1010', '99AB10BC99AB10BC99AB10BC99AB10BC', '99AB10BC99AB10BC');
		$this->user->insert($this->mysqli);
		$this->profile = new Profile(null, "Test", "Test2", "5555555555", "m", "012345", "http://www.cats.com/cat.jpg", $this->user->getUserId());
		$this->profile->insert($this->mysqli);
		$this->store = new Store(null, $this->profile->getProfileId(), "Pass Farms", "http://www.store.com/store.jpg", $date);
		$this->store->insert($this->mysqli);
		$this->location = new Location(null, "US", "NM", "Albuquerque", "87112", "2200 Camino de los Artesanos", null);
		$this->location->insert($this->mysqli);
		$this->storeLocation = new StoreLocation($this->store->getStoreId(),$this->location->getLocationId());
	}
	/**
	 * tears down the connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
// destroy the objects if they were created
		if($this->storeLocation !== null) {
			$this->storeLocation->delete($this->mysqli);
			$this->storeLocation = null;
		}
		if($this->location !== null) {
			$this->location->delete($this->mysqli);
			$this->location = null;
		}
		if($this->store !== null) {
			$this->store->delete($this->mysqli);
			$this->store = null;
		}
		if($this->profile !== null) {
			$this->profile->delete($this->mysqli);
			$this->profile = null;
		}
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
	 * test inserting a valid StoreLocation into mySQL
	 **/
	public function testInsertValidStoreLocation() {
// zeroth, ensure the StoreLocation and mySQL class are sane
		$this->assertNotNull($this->storeLocation);
		$this->assertNotNull($this->mysqli);
// first, insert the StoreLocation into mySQL
		$this->storeLocation->insert($this->mysqli);
// second, grab a StoreLocation from mySQL
		$mysqlStoreLocation = StoreLocation::getStoreLocationByStoreIdAndLocationId($this->mysqli, $this->storeLocation->getStoreId(), $this->storeLocation->getLocationId());
// third, assert the StoreLocation we have created and mySQL's StoreLocation are the same object
		$this->assertIdentical($this->storeLocation->getStoreId(), $mysqlStoreLocation->getStoreId());
		$this->assertIdentical($this->storeLocation->getLocationId(), $mysqlStoreLocation->getLocationId());
	}
	/**
	 * test inserting an invalid StoreLocation into mySQL
	 **/
	public function testInsertInvalidStoreLocation() {
// zeroth, ensure the StoreLocation and mySQL class are sane
		$this->assertNotNull($this->storeLocation);
		$this->assertNotNull($this->mysqli);
// first, set the store id and location id to an invented value that should never insert in the first place
		$this->storeLocation->setStoreId(42);
		$this->storeLocation->setLocationId(42);
// second, try to insert the StoreLocation and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->storeLocation->insert($this->mysqli);
// third, set the StoreLocation to null to prevent tearDown() from deleting a StoreLocation that never existed
		$this->storeLocation = null;
	}
	/**
	 * test deleting a StoreLocation from mySQL
	 **/
	public function testDeleteValidStoreLocation() {
// zeroth, ensure the StoreLocation and mySQL class are sane
		$this->assertNotNull($this->storeLocation);
		$this->assertNotNull($this->mysqli);
// first, assert the StoreLocation is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->storeLocation->insert($this->mysqli);
		$mysqlStoreLocation = StoreLocation::getStoreLocationByStoreIdAndLocationId($this->mysqli, $this->storeLocation->getStoreId(), $this->storeLocation->getLocationId());
		$this->assertIdentical($this->storeLocation->getStoreId(), $mysqlStoreLocation->getStoreId());
		$this->assertIdentical($this->storeLocation->getLocationId(), $mysqlStoreLocation->getLocationId());
// second, delete the StoreLocation from mySQL and re-grab it from mySQL and assert it does not exist
		$this->storeLocation->delete($this->mysqli);
		$mysqlStoreLocation = StoreLocation::getStoreLocationByStoreIdAndLocationId($this->mysqli, $this->storeLocation->getStoreId(), $this->storeLocation->getLocationId());
		$this->assertNull($mysqlStoreLocation);
// third, set the StoreLocation to null to prevent tearDown() from deleting a StoreLocation that has already been deleted
		$this->storeLocation = null;
	}
	/**
	 * test deleting a StoreLocation from mySQL that does not exist
	 **/
	public function testDeleteInvalidStoreLocation() {
// zeroth, ensure the StoreLocation and mySQL class are sane
		$this->assertNotNull($this->storeLocation);
		$this->assertNotNull($this->mysqli);
		var_dump($this->storeLocation);
		var_dump($this->mysqli);
// first, try to delete the StoreLocation before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		var_dump($this->mysqli);
		$this->storeLocation->delete($this->mysqli);
// second, set the StoreLocation to null to prevent tearDown() from deleting a StoreLocation that has already been deleted
		$this->storeLocation = null;
	}
	/**
	 * test insert valid StoreLocation
	 */
	public function testGetStoreLocationByValidStoreIdAndValidLocationId() {
		$this->assertNotNull($this->storeLocation);
		$this->assertNotNull($this->mysqli);
// first, insert the StoreLocation into mySQL
		$this->storeLocation->insert($this->mysqli);
// second, grab the StoreLocation from mySQL
		$mysqlStoreLocations = StoreLocation::getStoreLocationByStoreIdAndLocationId($this->mysqli, $this->storeLocation->getStoreId(), $this->storeLocation->getLocationId());
// third, assert the StoreLocation we have created and mySQL's StoreLocation are the same object
		foreach($mysqlStoreLocations as $mysqlStoreLocation) {
			$this->assertNotNull($mysqlStoreLocation->getStoreId());
			$this->assertTrue($mysqlStoreLocation->getStoreId() > 0);
			$this->assertNotNull($mysqlStoreLocation->getLocationId());
			$this->assertTrue($mysqlStoreLocation->getLocationId() > 0);
			$this->assertIdentical($this->storeLocation->getStoreId(), $mysqlStoreLocation->getStoreId());
			$this->assertIdentical($this->storeLocation->getLocationId(), $mysqlStoreLocation->getLocationId());
		}
	}
}
?>