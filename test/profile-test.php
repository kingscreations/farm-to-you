<?php
// first, require the SimpleTest framework <http://www.simpletest.org/>
// this path is *NOT* universal, but deployed on the bootcamp-coders server
require_once("/usr/lib/php5/simpletest/autorun.php");

// next, require the class from the project under scrutiny
require_once("../php/classes/profile.php");

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

/**
 * unit test for the Profile class
 *
 * This is a simpletest test case for the CRUD methods of the Profile class
 *
 * @see profile
 * @author Jason King <jason@kingscreations.org>
 *
 **/

class ProfileTest extends UnitTestCase {
	/**
	 * mysqli object shared amongst all tests
	 **/
	private $mysqli = null;
	/**
	 * instance of the object we are testing with
	 **/
	private $profile = null;

	// this section contains member variables with constants needed for creating a new profile
	/**
	 * profile id of the person who is inserting the test Profile
	 * @deprecated a parent class of type Profile should be used here instead
	 **/
	/**
	 * first name of the test user
	 **/
	private $firstName = "Billy Jo Bob";
	/**
	 * last name of the test user
	 **/
	private $lastName = "Suspender";
	/**
	 * phone number of the test user
	 **/
	private $phone = "(123)456-7890";
	/**
	 * user type of the test user
	 **/
	private $profileType = "farmer";
	/**
	 * strype issued token of the test user
	 **/
	private $customerToken = "token1234567890";
	/**
	 * picture file of the test user
	 **/
	private $imagepath = "picture.jpg";
	/**
	 * user Id of the test user
	 **/
	private $userId = 1;
	/**
	 * sets up the mySQL connection for this test
	 **/
	public function setUp() {
		// first, connect to mysqli
		mysqli_report(MYSQLI_REPORT_STRICT);
		$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
		$this->mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

		// second, create an instance of the object under scrutiny
		$this->profile = new Profile(null, $this->firstName, $this->lastName, $this->phone, $this->profileType, $this->customerToken, $this->imagePath, $this->userId);
	}

	/**
	 * tears down the connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		// destroy the object if it was created
		if($this->profile !== null) {
			$this->profile->delete($this->mysqli);
			$this->profile = null;
		}

		// disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}

	/**
	 * test inserting a valid Profile into mySQL
	 **/
	public function testInsertValidProfile() {
		// zeroth, ensure the Profile and mySQL class are sane
		$this->assertNotNull($this->profile);
		$this->assertNotNull($this->mysqli);

		// first, insert the Profile into mySQL
		$this->profile->insert($this->mysqli);

		// second, grab a Profile from mySQL
		$mysqlProfile = Profile::getProfileByProfileId($this->mysqli, $this->profile->getProfileId());

		// third, assert the Profile we have created and mySQL's Profile are the same object
		$this->assertIdentical($this->profile->getProfileId(), $mysqlProfile->getProfileId());
		$this->assertIdentical($this->profile->getFirstName(), $mysqlProfile->getFirstName());
		$this->assertIdentical($this->profile->getLastName(), $mysqlProfile->getLastName());
		$this->assertIdentical($this->profile->getPhone(), $mysqlProfile->getPhone());
		$this->assertIdentical($this->profile->getProfileType(), $mysqlProfile->getProfileType());
		$this->assertIdentical($this->profile->getCustomerToken(), $mysqlProfile->getCustomerToken());
		$this->assertIdentical($this->profile->getImagePath(), $mysqlProfile->getImagePath());
		$this->assertIdentical($this->profile->getUserId(), $mysqlProfile->getUserId());
	}

	/**
	 * test inserting an invalid Profile into mySQL
	 **/
	public function testInsertInvalidProfile() {
		// zeroth, ensure the Profile and mySQL class are sane
		$this->assertNotNull($this->profile);
		$this->assertNotNull($this->mysqli);

		// first, set the profile id to an invented value that should never insert in the first place
		$this->profile->setProfileId(42);

		// second, try to insert the Profile and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->profile->insert($this->mysqli);

		// third, set the Profile to null to prevent tearDown() from deleting a Profile that never existed
		$this->profile = null;
	}

	/**
	 * test deleting a Profile from mySQL
	 **/
	public function testDeleteValidProfile() {
		// zeroth, ensure the Profile and mySQL class are sane
		$this->assertNotNull($this->profile);
		$this->assertNotNull($this->mysqli);

		// first, assert the Profile is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->profile->insert($this->mysqli);
		$mysqlProfile = Profile::getProfileByProfileId($this->mysqli, $this->profile->getProfileId());
		$this->assertIdentical($this->profile->getProfileId(), $mysqlProfile->getProfileId());

		// second, delete the Profile from mySQL and re-grab it from mySQL and assert it does not exist
		$this->profile->delete($this->mysqli);
		$mysqlProfile = Profile::getProfileByProfileId($this->mysqli, $this->profile->getProfileId());
		$this->assertNull($mysqlProfile);

		// third, set the Profile to null to prevent tearDown() from deleting a Profile that has already been deleted
		$this->profile = null;
	}

	/**
	 * test deleting a Profile from mySQL that does not exist
	 **/
	public function testDeleteInvalidProfile() {
		// zeroth, ensure the Profile and mySQL class are sane
		$this->assertNotNull($this->profile);
		$this->assertNotNull($this->mysqli);

		// first, try to delete the Profile before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->profile->delete($this->mysqli);

		// second, set the Profile to null to prevent tearDown() from deleting a Profile that has already been deleted
		$this->profile = null;
	}

	/**
	 * test updating a Profile from mySQL
	 **/
	public function testUpdateValidProfile() {
		// zeroth, ensure the Profile and mySQL class are sane
		$this->assertNotNull($this->profile);
		$this->assertNotNull($this->mysqli);

		// first, assert the Profile is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->profile->insert($this->mysqli);
		$mysqlProfile = Profile::getProfileByProfileId($this->mysqli, $this->profile->getProfileId());
		$this->assertIdentical($this->profile->getProfileId(), $mysqlProfile->getProfileId());

		// second, change the Profile, update it mySQL
		$newContent = "My unit tests updated everything!";
		$this->profile->setProfileContent($newContent);
		$this->profile->update($this->mysqli);

		// third, re-grab the Profile from mySQL
		$mysqlProfile = Profile::getProfileByProfileId($this->mysqli, $this->profile->getProfileId());
		$this->assertNotNull($mysqlProfile);

		// fourth, assert the Profile we have updated and mySQL's Profile are the same object
		$this->assertIdentical($this->profile->getProfileId(), $mysqlProfile->getProfileId());
		$this->assertIdentical($this->profile->getFirstName(), $mysqlProfile->getFirstName());
		$this->assertIdentical($this->profile->getLastName(), $mysqlProfile->getLastName());
		$this->assertIdentical($this->profile->getPhone(), $mysqlProfile->getPhone());
		$this->assertIdentical($this->profile->getProfileType(), $mysqlProfile->getProfileType());
		$this->assertIdentical($this->profile->getCustomerToken(), $mysqlProfile->getCustomerToken());
		$this->assertIdentical($this->profile->getImagePath(), $mysqlProfile->getImagePath());
		$this->assertIdentical($this->profile->getUserId(), $mysqlProfile->getUserId());
	}

	/**
	 * test updating a Profile from mySQL that does not exist
	 **/
	public function testUpdateInvalidProfile() {
		// zeroth, ensure the Profile and mySQL class are sane
		$this->assertNotNull($this->profile);
		$this->assertNotNull($this->mysqli);

		// first, try to update the Profile before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->profile->update($this->mysqli);

		// second, set the Profile to null to prevent tearDown() from deleting a Profile that has already been deleted
		$this->profile = null;
	}
}
?>