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
	private $hash = "cd5a4f0b677843c4d656579250ccb7aada88031641cf05d203ca021b135ccec2";
	/**
	 * salt to add to hash of test user
	 **/
	private $phone = "jasonkingisgreatjasonkingisgreat";
	/**
	 * activation value for test user
	 **/
	private $profileType = "greatisjasonking";
			/**
	 * sets up the mySQL connection for this test
	 **/
	public function setUp() {
		// first, connect to mysqli
		mysqli_report(MYSQLI_REPORT_STRICT);
		$this->mysqli = new mysqli("localhost", "--USERNAME--", "--PASSWORD--", "--DATABASE--");

		// second, create an instance of the object under scrutiny
		$this->tweetDate = new DateTime();
		$this->user = new Tweet(null, $this->profileId, $this->tweetContent, $this->tweetDate);
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
	 * test inserting a valid Tweet into mySQL
	 **/
	public function testInsertValidTweet() {
		// zeroth, ensure the Tweet and mySQL class are sane
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->mysqli);

		// first, insert the Tweet into mySQL
		$this->user->insert($this->mysqli);

		// second, grab a Tweet from mySQL
		$mysqlTweet = Tweet::getTweetByTweetId($this->mysqli, $this->user->getTweetId());

		// third, assert the Tweet we have created and mySQL's Tweet are the same object
		$this->assertIdentical($this->user->getTweetId(), $mysqlTweet->getTweetId());
		$this->assertIdentical($this->user->getProfileId(), $mysqlTweet->getProfileId());
		$this->assertIdentical($this->user->getTweetContent(), $mysqlTweet->getTweetContent());
		$this->assertIdentical($this->user->getTweetDate(), $mysqlTweet->getTweetDate());
	}

	/**
	 * test inserting an invalid Tweet into mySQL
	 **/
	public function testInsertInvalidTweet() {
		// zeroth, ensure the Tweet and mySQL class are sane
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->mysqli);

		// first, set the tweet id to an invented value that should never insert in the first place
		$this->user->setTweetId(42);

		// second, try to insert the Tweet and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->user->insert($this->mysqli);

		// third, set the Tweet to null to prevent tearDown() from deleting a Tweet that never existed
		$this->user = null;
	}

	/**
	 * test deleting a Tweet from mySQL
	 **/
	public function testDeleteValidTweet() {
		// zeroth, ensure the Tweet and mySQL class are sane
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->mysqli);

		// first, assert the Tweet is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->user->insert($this->mysqli);
		$mysqlTweet = Tweet::getTweetByTweetId($this->mysqli, $this->user->getTweetId());
		$this->assertIdentical($this->user->getTweetId(), $mysqlTweet->getTweetId());

		// second, delete the Tweet from mySQL and re-grab it from mySQL and assert it does not exist
		$this->user->delete($this->mysqli);
		$mysqlTweet = Tweet::getTweetByTweetId($this->mysqli, $this->user->getTweetId());
		$this->assertNull($mysqlTweet);

		// third, set the Tweet to null to prevent tearDown() from deleting a Tweet that has already been deleted
		$this->user = null;
	}

	/**
	 * test deleting a Tweet from mySQL that does not exist
	 **/
	public function testDeleteInvalidTweet() {
		// zeroth, ensure the Tweet and mySQL class are sane
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->mysqli);

		// first, try to delete the Tweet before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->user->delete($this->mysqli);

		// second, set the Tweet to null to prevent tearDown() from deleting a Tweet that has already been deleted
		$this->user = null;
	}

	/**
	 * test updating a Tweet from mySQL
	 **/
	public function testUpdateValidTweet() {
		// zeroth, ensure the Tweet and mySQL class are sane
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->mysqli);

		// first, assert the Tweet is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->user->insert($this->mysqli);
		$mysqlTweet = Tweet::getTweetByTweetId($this->mysqli, $this->user->getTweetId());
		$this->assertIdentical($this->user->getTweetId(), $mysqlTweet->getTweetId());

		// second, change the Tweet, update it mySQL
		$newContent = "My unit tests updated everything!";
		$this->user->setTweetContent($newContent);
		$this->user->update($this->mysqli);

		// third, re-grab the Tweet from mySQL
		$mysqlTweet = Tweet::getTweetByTweetId($this->mysqli, $this->user->getTweetId());
		$this->assertNotNull($mysqlTweet);

		// fourth, assert the Tweet we have updated and mySQL's Tweet are the same object
		$this->assertIdentical($this->user->getTweetId(), $mysqlTweet->getTweetId());
		$this->assertIdentical($this->user->getProfileId(), $mysqlTweet->getProfileId());
		$this->assertIdentical($this->user->getTweetContent(), $mysqlTweet->getTweetContent());
		$this->assertIdentical($this->user->getTweetDate(), $mysqlTweet->getTweetDate());
	}

	/**
	 * test updating a Tweet from mySQL that does not exist
	 **/
	public function testUpdateInvalidTweet() {
		// zeroth, ensure the Tweet and mySQL class are sane
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->mysqli);

		// first, try to update the Tweet before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->user->update($this->mysqli);

		// second, set the Tweet to null to prevent tearDown() from deleting a Tweet that has already been deleted
		$this->user = null;
	}
}
?>