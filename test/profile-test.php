<?php
// first, require the SimpleTest framework <http://www.simpletest.org/>
require_once("/usr/lib/php5/simpletest/autorun.php");

// require the encrypted configuration functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// the classes to test
require_once("../php/classes/user.php");
require_once("../php/classes/profile.php");



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
	 * first instance of the object we are testing with
	 **/
	private $profile1 = null;
	/**
	 * second instance of the object we are testing with
	 **/
	private $profile2 = null;

// this section contains member variables with constants needed for creating a new profile
	private $firstName1 = "Ronald";
	/**
	 * first name of the second test user
	 **/
	private $firstName2 = "Dylan";
	/**
	 * last name of the first test user
	 **/
	private $lastName1 = "McDonald";
	/**
	 * last name of the second test user
	 **/
	private $lastName2 = "McDonald";
	/**
	 * phone number of the first test user
	 **/
	private $phone1 = "(505)123-4567";
	/**
	 * phone number of the second test user
	 **/
	private $phone2 = "(505) 246-8100";
	/**
	 * profile type of the first test user
	 **/
	private $profileType1 = "c";
	/**
	 * profile type of the second test user
	 **/
	private $profileType2 = "m";
	/**
	 * Strype issued customer token for first test user
	 **/
	private $customerToken1 = "111";
	/**
	 * Strype issued customer token for the second test user
	 **/
	private $customerToken2 = "222";
	/**
	 * image file for the first test user
	 **/
	private $imagePath1 = "clown1.img";
	/**
	 * image file for the second test user
	 **/
	private $imagePath2 = "clown2.img";
	/**
	 * @var int user id for the first test user. This is a foreign key.
	 **/
	private $userId = 1;

	/**
	 * sets up the mySQL connection for this test
	 **/
	public function setUp() {
		// get the credentials information from the server
		$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";
		$configArray = readConfig($configFile);

		// second, create an instance of the object under scrutiny
		$this->profile1 = new Profile(null, $this->firstName1, $this->lastName1, $this->phone1, $this->profileType1, $this->customerToken1, $this->imagePath1, $this->userId);
		$this->profile2 = new Profile(null, $this->firstName2, $this->lastName2, $this->phone2, $this->profileType2, $this->customerToken2, $this->imagePath2, $this->userId);
	}

	/**
	 * tears down the connection to mySQL and deletes the test instance object
	 **/
public function tearDown() {
// destroy the object if it was created
	if($this->profile1 !== null && $this->profile1->getProfileId() !== null) {
		$this->profile1->delete($this->mysqli);
		$this->profile1 = null;
	}

	if($this->profile2 !== null && $this->profile2->getProfileId() !== null) {
		$this->profile2->delete($this->mysqli);
		$this->profile2 = null;
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
		$this->assertNotNull($this->profile1);
		$this->assertNotNull($this->mysqli);

		// first, insert the Profile into mySQL
		$this->profile1->insert($this->mysqli);

		// second, grab a Profile from mySQL
		$mysqlProfile = Profile::getProfileByProfileId($this->mysqli, $this->profile1->getProfileId());

		// third, assert the Profile we have created and mySQL's Profile are the same object
		$this->assertIdentical($this->profile1->getProfileId(), $mysqlProfile->getProfileId());
		$this->assertIdentical($this->profile1->getFirstName(), $mysqlProfile->getFirstName());
		$this->assertIdentical($this->profile1->getLastName(), $mysqlProfile->getLastName());
		$this->assertIdentical($this->profile1->getPhone(), $mysqlProfile->getPhone());
		$this->assertIdentical($this->profile1->getProfileType(), $mysqlProfile->getProfileType());
		$this->assertIdentical($this->profile1->getCustomerToken(), $mysqlProfile->getCustomerToken());
		$this->assertIdentical($this->profile1->getImagePath(), $mysqlProfile->getImagePath());
		$this->assertIdentical($this->profile1->getUserId(), $mysqlProfile->getUserId());
	}

	/**
	 * test inserting an invalid Profile into mySQL
	 **/
	public function testInsertInvalidProfile() {
		// zeroth, ensure the Profile and mySQL class are sane
		$this->assertNotNull($this->profile1);
		$this->assertNotNull($this->mysqli);

		// first, set the profile id to an invented value that should never insert in the first place
		$this->profile1->setProfileId(42);

		// second, try to insert the Profile and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->profile1->insert($this->mysqli);

		// third, set the Profile to null to prevent tearDown() from deleting a Profile that never existed
		$this->profile1 = null;
	}

	/**
	 * test deleting a Profile from mySQL
	 **/
	public function testDeleteValidProfile() {
		// zeroth, ensure the Profile and mySQL class are sane
		$this->assertNotNull($this->profile1);
		$this->assertNotNull($this->mysqli);

		// first, assert the Profile is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->profile1->insert($this->mysqli);
		$mysqlProfile = Profile::getProfileByProfileId($this->mysqli, $this->profile1->getProfileId());
		$this->assertIdentical($this->profile1->getProfileId(), $mysqlProfile->getProfileId());

		// second, delete the Profile from mySQL and re-grab it from mySQL and assert it does not exist
		$this->profile1->delete($this->mysqli);
		$mysqlProfile = Profile::getProfileByProfileId($this->mysqli, $this->profile1->getProfileId());
		$this->assertNull($mysqlProfile);

		// third, set the Profile to null to prevent tearDown() from deleting a Profile that has already been deleted
		$this->profile1 = null;
	}

	/**
	 * test deleting a non existent Profile from mySQL
	 **/
	public function testDeleteInvalidProfile() {
		// zeroth, ensure the Profile and mySQL class are sane
		$this->assertNotNull($this->profile1);
		$this->assertNotNull($this->mysqli);

		// first, try to delete the Profile before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->profile1->delete($this->mysqli);

		// second, set the Profile to null to prevent tearDown() from deleting a Profile that has already been deleted
		$this->profile1 = null;
	}

	/**
	 * test updating a Profile from mySQL
	 **/
	public function testUpdateValidProfile() {
		// zeroth, ensure the Profile and mySQL class are sane
		$this->assertNotNull($this->profile1);
		$this->assertNotNull($this->mysqli);

		// first, assert the Profile is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->profile1->insert($this->mysqli);
		$mysqlProfile = Profile::getProfileByProfileId($this->mysqli, $this->profile1->getProfileId());
		$this->assertIdentical($this->profile1->getProfileId(), $mysqlProfile->getProfileId());

		// second, change the Profile, update it mySQL
		$newLastName = "O'Unit Tests";
		$this->profile1->setLastName($newLastName);
		$this->profile1->update($this->mysqli);

		// third, re-grab the Profile from mySQL
		$mysqlProfile = Profile::getProfileByProfileId($this->mysqli, $this->profile1->getProfileId());
		$this->assertNotNull($mysqlProfile);

		// fourth, assert the Profile we have updated and mySQL's Profile are the same object
		$this->assertIdentical($this->profile1->getProfileId(), $mysqlProfile->getProfileId());
		$this->assertIdentical($this->profile1->getFirstName(), $mysqlProfile->getFirstName());
		$this->assertIdentical($this->profile1->getLastName(), $mysqlProfile->getLastName());
		$this->assertIdentical($this->profile1->getPhone(), $mysqlProfile->getPhone());
		$this->assertIdentical($this->profile1->getProfileType(), $mysqlProfile->getProfileType());
		$this->assertIdentical($this->profile1->getCustomerToken(), $mysqlProfile->getCustomerToken());
		$this->assertIdentical($this->profile1->getImagePath(), $mysqlProfile->getImagePath());
		$this->assertIdentical($this->profile1->getUserId(), $mysqlProfile->getUserId());
	}

	/**
	 * test updating a non existent Profile from mySQL
	 **/
	public function testUpdateInvalidProfile() {
		// zeroth, ensure the Profile and mySQL class are sane
		$this->assertNotNull($this->profile1);
		$this->assertNotNull($this->mysqli);

		// first, try to update the Profile before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->profile1->update($this->mysqli);

		// second, set the Profile to null to prevent tearDown() from deleting a Profile that has already been deleted
		$this->profile1 = null;
	}
	/**
	 *test getting a valid profile by profileId
	 **/
	public function testGetValidProfileByProfileId() {
		$this->assertNotNull($this->profile1);
		$this->assertNotNull($this->mysqli);

		// first, assert the Profile is inserted into mySQL by grabbing it and asserting the primary key
		$this->profile1->insert($this->mysqli);
		$mysqlProfile = Profile::getProfileByProfileId($this->mysqli, $this->profile1->getProfileId());
		$this->assertIdentical($this->profile1->getProfileId(), $mysqlProfile->getProfileId());
		}

	/**
	 * test getting a valid profile by using an invalid profileId
	**/
	public function testGetInvalidProfileByProfileId() {
		// first, assert the mySQL class is sane
		$this->assertNotNull($this->mysqli);

		// grab a Profile that could never exist
		$mysqlProfile = Profile::getProfileByProfileId($this->mysqli, 12);
		$this->assertNull($mysqlProfile);
	}
	/**
	 * test getting multiple profiles from mySQL by lastName
	 **/
	public function testGetValidProfileByLastName() {
		$this->assertNotNull($this->profile1);
		$this->assertNotNull($this->profile2);
		$this->assertNotNull($this->mysqli);

		// then insert both profiles
		$this->profile1->insert($this->mysqli);
		$this->profile2->insert($this->mysqli);

		// grab an array of Profiles from mySQL and assert we have an array
		$myLittlePony = "unit tests";
		$profiles = Profile::getProfileByLastName($this->mysqli, $myLittlePony);
		$this->assertIsA($profiles, "array");
		$this->assertIdentical(count($profiles), 2);

		//finally, verify each profile by asserting the primary key and the select criteria
		foreach($profiles as $profile) {
			$this->assertTrue($profile->getProfileId() > 0);
			$this->assertTrue(strpos($profile->getLastName(), $myLittlePony) >= 0);
		}
	}
	/**
	 * test grabbing no profiles from mySQL by a non existent last name
	 **/
	public function testSelectInvalidProfileByLastName() {
	// zeroth, ensure the Profile and mySQL class are sane
		$this->assertNotNull($this->profile1);
		$this->assertNotNull($this->profile2);
		$this->assertNotNull($this->mysqli);

	// first, insert the two test profiles
		$this->profile1->insert($this->mysqli);
		$this->profile2->insert($this->mysqli);

	// second, try to grab an array of Profiles from mySQL and assert null
		$myLittlePony = "ImaBlackHatHacker";
		$profiles = Profile::getProfileByLastName($this->mysqli, $myLittlePony);
		$this->assertNull($profiles);
	}
}
?>