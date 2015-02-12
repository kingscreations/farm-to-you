<?php

// first, require SimpleTest framework <http://www.simpletest.org/>
// this path is *NOT* universal, but deployed on the bootcamp-coders server
require_once ("/usr/lib/php5/simpletest/autorun.php");

// next, require the class from the project under scrutiny

require_once ("../php/classes/location.php");

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
/**
 * Unit test for the Location class
 *
 * This is a SimpleTest test case for the CRUD methods of the Location class.
 *
 * @see Location
 * @author Alonso Indacochea <alonso@hermesdevelopment.com>
 **/
class LocationTest extends UnitTestCase {
	/**
	 * mysqli object shared amongst all tests
	 **/
	private $mysqli = null;
	/**
	 * instance of the object we are testing with
	 **/
	private $location = null;
	/**
	 * instance of the second object we are testing with
	 **/
	private $location2 = null;

	// this section contains member variables with constants needed for creating a new location
	private $locationName = "Home";
	/**
	 * country of location
	 **/
	private $country = "US";
	/**
	 * state of location
	 **/
	private $state = "NM";
	/**
	 * city of location
	 **/
	private $city = "Corrales";
	/**
	 * zip code of location
	 **/
	private $zipCode = "87048";
	/**
	 * address line 1 of location
	 **/
	private $address1 = "1228 W La Entrada";
	/**
	 * address line 2 of location
	 **/
	private $address2 = null;

	/**
	 * sets up the mySQL connection for this test
	 **/
	public function setUp() {
		// first, connect to mysqli
		mysqli_report(MYSQLI_REPORT_STRICT);
		$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
		$configArray = readConfig($configFile);
		$this->mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"], $configArray["database"]);

		// second, create an instance of the object under scrutiny
		$this->location = new Location(null, $this->locationName, $this->country, $this->state, $this->city, $this->zipCode, $this->address1, $this->address2);
	}

	/**
	 * tears down the connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		// destroy the objects if they were created
		if($this->location !== null) {
			$this->location->delete($this->mysqli);
			$this->location = null;
		}
		if($this->location2 !== null) {
			$this->location2->delete($this->mysqli);
			$this->location2 = null;
		}
		// disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}

	/**
	 * test inserting a valid Location into mySQL
	 **/
	public function testInsertValidLocation() {
		// zeroth, ensure the Location and mySQL class are sane

		$this->assertNotNull($this->location);
		$this->assertNotNull($this->mysqli);

		// first, insert the Location into mySQL
		$this->location->insert($this->mysqli);

		// second, grab a Location from mySQL
		$mysqlLocation = Location::getLocationByLocationId($this->mysqli, $this->location->getLocationId());

		// third, assert the Location we have created and mySQL's Location are the same object
		$this->assertIdentical($this->location->getLocationId(), $mysqlLocation->getLocationId());
		$this->assertIdentical($this->location->getLocationName(), $mysqlLocation->getLocationName());
		$this->assertIdentical($this->location->getCountry(), $mysqlLocation->getCountry());
		$this->assertIdentical($this->location->getState(), $mysqlLocation->getState());
		$this->assertIdentical($this->location->getCity(), $mysqlLocation->getCity());
		$this->assertIdentical($this->location->getZipCode(), $mysqlLocation->getZipCode());
		$this->assertIdentical($this->location->getAddress1(), $mysqlLocation->getAddress1());
		$this->assertIdentical($this->location->getAddress2(), $mysqlLocation->getAddress2());

	}

	/**
	 * test inserting an invalid Location into mySQL
	 **/
	public function testInsertInvalidLocation() {
		// zeroth, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location);
		$this->assertNotNull($this->mysqli);

		// first, set the location id to an invented value that should never insert in the first place
		$this->location->setLocationId(1042);

		// second, try to insert the Location and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->location->insert($this->mysqli);

		// third, set the Location to null to prevent tearDown() from deleting a Location that never existed
		$this->location = null;
	}

	/**
	 * test deleting a Location from mySQL
	 **/
	public function testDeleteValidLocation() {
		// zeroth, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location);
		$this->assertNotNull($this->mysqli);

		// first, assert the Location is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->location->insert($this->mysqli);
		$mysqlLocation = Location::getLocationByLocationId($this->mysqli, $this->location->getLocationId());
		$this->assertIdentical($this->location->getLocationId(), $mysqlLocation->getLocationId());

		// second, delete the Location from mySQL and re-grab it from mySQL and assert it does not exist
		$this->location->delete($this->mysqli);
		$mysqlLocation = Location::getLocationByLocationId($this->mysqli, $this->location->getLocationId());
		$this->assertNull($mysqlLocation);

		// third, set the Location to null to prevent tearDown() from deleting a Location that has already been deleted
		$this->location = null;
	}

	/**
	 * test deleting a Location from mySQL that does not exist
	 **/
	public function testDeleteInvalidLocation() {
		// zeroth, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location);
		$this->assertNotNull($this->mysqli);

		// first, try to delete the Location before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->location->delete($this->mysqli);

		// second, set the Location to null to prevent tearDown() from deleting a Location that has already been deleted
		$this->location = null;
	}

	/**
	 * test updating a Location from mySQL
	 **/
	public function testUpdateValidLocation() {
		// zeroth, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location);
		$this->assertNotNull($this->mysqli);

		// first, assert the Location is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->location->insert($this->mysqli);
		$mysqlLocation = Location::getLocationByLocationId($this->mysqli, $this->location->getLocationId());
		$this->assertIdentical($this->location->getLocationId(), $mysqlLocation->getLocationId());

		// second, change the Location, update it mySQL
		$newCountry = "CA";
		$this->location->setCountry($newCountry);
		$this->location->update($this->mysqli);

		// third, re-grab the Location from mySQL
		$mysqlLocation = Location::getLocationByLocationId($this->mysqli, $this->location->getLocationId());
		$this->assertNotNull($mysqlLocation);

		// fourth, assert the Location we have updated and mySQL's Location are the same object
		$this->assertIdentical($this->location->getLocationId(), $mysqlLocation->getLocationId());
		$this->assertIdentical($this->location->getLocationName(), $mysqlLocation->getLocationName());
		$this->assertIdentical($this->location->getCountry(), $mysqlLocation->getCountry());
		$this->assertIdentical($this->location->getState(), $mysqlLocation->getState());
		$this->assertIdentical($this->location->getCity(), $mysqlLocation->getCity());
		$this->assertIdentical($this->location->getZipCode(), $mysqlLocation->getZipCode());
		$this->assertIdentical($this->location->getAddress1(), $mysqlLocation->getAddress1());
		$this->assertIdentical($this->location->getAddress2(), $mysqlLocation->getAddress2());
	}

	/**
	 * test updating a Location from mySQL that does not exist
	 **/
	public function testUpdateInvalidLocation() {
		// zeroth, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location);
		$this->assertNotNull($this->mysqli);

		// first, try to update the Location before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->location->update($this->mysqli);

		// second, set the Location to null to prevent tearDown() from deleting a Location that has already been deleted
		$this->location = null;
	}
	/**
	 * test getLocationByValidCity by inserting two identical Locations into mySQL, calling them with getLocationByCity,
	 * and asserting mySQL Locations and original Locations are identical
	 **/
	public function testGetLocationByValidCity() {
		// zeroth, create second Location
		$this->location2 = new Location(null, $this->locationName, $this->country, $this->state, $this->city, $this->zipCode, $this->address1, $this->address2);

		// zeroth #2, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location);
		$this->assertNotNull($this->location2);
		$this->assertNotNull($this->mysqli);

		// first, insert the Location into mySQL
		$this->location->insert($this->mysqli);
		$this->location2->insert($this->mysqli);

		// second, grab the Locations from mySQL
		$mysqlLocations = Location::getLocationByCity($this->mysqli, $this->city);

		// third, assert the Locations we have created and mySQL's Locations are the same object
		foreach($mysqlLocations as $mysqlLocation) {
			$this->assertNotNull($mysqlLocation->getLocationId());
			$this->assertTrue($mysqlLocation->getLocationId() > 0);
			$this->assertIdentical($this->location->getCity(), $mysqlLocation->getCity());
			$this->assertIdentical($this->location2->getCity(), $mysqlLocation->getCity());
		}
	}
	/**
	 * test getLocationByInvalidCity by inserting two identical Locations into mySQL, searching for a different city, and
	 * asserting that the result is null
	 **/
	public function testGetLocationByInvalidCity() {
		// zeroth, create second Location
		$this->location2 = new Location(null, $this->locationName, $this->country, $this->state, $this->city, $this->zipCode, $this->address1, $this->address2);

		// zeroth #2, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location);
		$this->assertNotNull($this->location2);
		$this->assertNotNull($this->mysqli);

		// first, insert the Location into mySQL
		$this->location->insert($this->mysqli);
		$this->location2->insert($this->mysqli);

		// second, grab the Locations from mySQL
		$mysqlLocations = Location::getLocationByCity($this->mysqli, "Ciudad de México");

		// third, assert results array is null
		$this->assertNull($mysqlLocations);
	}
	/**
	 * test getLocationByValidLocationName by inserting two identical Locations into mySQL, calling them with getLocationByLocationName,
	 * and asserting mySQL Locations and original Locations are identical
	 **/
	public function testGetLocationByValidLocationName() {
		// zeroth, create second Location
		$this->location2 = new Location(null, $this->locationName, $this->country, $this->state, $this->city, $this->zipCode, $this->address1, $this->address2);

		// zeroth #2, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location);
		$this->assertNotNull($this->location2);
		$this->assertNotNull($this->mysqli);
		// first, insert the Location into mySQL
		$this->location->insert($this->mysqli);
		$this->location2->insert($this->mysqli);
		// second, grab the Locations from mySQL
		$mysqlLocations = Location::getLocationByLocationName($this->mysqli, $this->locationName);

		// third, assert the Locations we have created and mySQL's Locations are the same object
		foreach($mysqlLocations as $mysqlLocation) {
			$this->assertNotNull($mysqlLocation->getLocationId());
			$this->assertTrue($mysqlLocation->getLocationId() > 0);
			$this->assertIdentical($this->location->getLocationName(), $mysqlLocation->getLocationName());
			$this->assertIdentical($this->location2->getLocationName(), $mysqlLocation->getLocationName());
		}
	}
	/**
	 * test getLocationByInvalidLocationName by inserting two identical Locations into mySQL, searching for
	 * a different location name, and asserting that the result is null
	 **/
	public function testGetLocationByInvalidLocationName() {
		// zeroth, create second Location
		$this->location2 = new Location(null, $this->locationName, $this->country, $this->state, $this->city, $this->zipCode, $this->address1, $this->address2);

		// zeroth #2, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location);
		$this->assertNotNull($this->location2);
		$this->assertNotNull($this->mysqli);

		// first, insert the Location into mySQL
		$this->location->insert($this->mysqli);
		$this->location2->insert($this->mysqli);

		// second, grab the Locations from mySQL
		$mysqlLocations = Location::getLocationByLocationName($this->mysqli, "Away");

		// third, assert results array is null
		$this->assertNull($mysqlLocations);
	}
	/**
	 * test getLocationByValidZipCode by inserting two identical Locations into mySQL, calling them with getLocationByZipCode,
	 * and asserting mySQL Locations and original Locations are identical
	 **/
	public function testGetLocationByValidZipCode() {
		// zeroth, create second Location
		$this->location2 = new Location(null, $this->locationName, $this->country, $this->state, $this->city, $this->zipCode, $this->address1, $this->address2);

		// zeroth #2, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location);
		$this->assertNotNull($this->location2);
		$this->assertNotNull($this->mysqli);

		// first, insert the Location into mySQL
		$this->location->insert($this->mysqli);
		$this->location2->insert($this->mysqli);

		// second, grab the Locations from mySQL
		$mysqlLocations = Location::getLocationByZipCode($this->mysqli, $this->zipCode);

		// third, assert the Locations we have created and mySQL's Locations are the same object
		foreach($mysqlLocations as $mysqlLocation) {
			$this->assertNotNull($mysqlLocation->getLocationId());
			$this->assertTrue($mysqlLocation->getLocationId() > 0);
			$this->assertIdentical($this->location->getZipCode(), $mysqlLocation->getZipCode());
			$this->assertIdentical($this->location2->getZipCode(), $mysqlLocation->getZipCode());
		}
	}
	/**
	 * test getLocationByInvalidZipCode by inserting two identical Locations into mySQL, searching for a different zip
	 * code, and asserting that the result is null
	 **/
	public function testGetLocationByInvalidZipCode() {
		// zeroth, create second Location
		$this->location2 = new Location(null, $this->locationName, $this->country, $this->state, $this->city, $this->zipCode, $this->address1, $this->address2);

		// zeroth #2, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location);
		$this->assertNotNull($this->location2);
		$this->assertNotNull($this->mysqli);

		// first, insert the Location into mySQL
		$this->location->insert($this->mysqli);
		$this->location2->insert($this->mysqli);

		// second, grab the Locations from mySQL
		$mysqlLocations = Location::getLocationByZipCode($this->mysqli, "26847");

		// third, assert results array is null
		$this->assertNull($mysqlLocations);
	}
	/**
	 * test getLocationByValidAddress1 by inserting two identical Locations into mySQL, calling them with getLocationByAddress1,
	 * and asserting mySQL Locations and original Locations are identical
	 **/
	public function testGetLocationByValidAddress1() {
		// zeroth, create second Location
		$this->location2 = new Location(null, $this->locationName, $this->country, $this->state, $this->city, $this->zipCode, $this->address1, $this->address2);

		// zeroth #2, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location);
		$this->assertNotNull($this->location2);
		$this->assertNotNull($this->mysqli);

		// first, insert the Location into mySQL
		$this->location->insert($this->mysqli);
		$this->location2->insert($this->mysqli);

		// second, grab the Locations from mySQL
		$mysqlLocations = Location::getLocationByAddress1($this->mysqli, $this->address1);

		// third, assert the Locations we have created and mySQL's Locations are the same object
		foreach($mysqlLocations as $mysqlLocation) {
			$this->assertNotNull($mysqlLocation->getLocationId());
			$this->assertTrue($mysqlLocation->getLocationId() > 0);
			$this->assertIdentical($this->location->getAddress1(), $mysqlLocation->getAddress1());
			$this->assertIdentical($this->location2->getAddress1(), $mysqlLocation->getAddress1());
		}
	}
	/**
	 * test getLocationByInvalidAddress1 by inserting two identical Locations into mySQL, searching for a different
	 * address line 1, and asserting that the result is null
	 **/
	public function testGetLocationByInvalidAddress1() {
		// zeroth, create second Location
		$this->location2 = new Location(null, $this->locationName, $this->country, $this->state, $this->city, $this->zipCode, $this->address1, $this->address2);

		// zeroth #2, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location);
		$this->assertNotNull($this->location2);
		$this->assertNotNull($this->mysqli);

		// first, insert the Location into mySQL
		$this->location->insert($this->mysqli);
		$this->location2->insert($this->mysqli);

		// second, grab the Locations from mySQL
		$mysqlLocations = Location::getLocationByAddress1($this->mysqli, "203 Judy Street");

		// third, assert results array is null
		$this->assertNull($mysqlLocations);
	}
}
?>