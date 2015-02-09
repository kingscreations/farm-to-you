<?php
// first, require the SimpleTest framework <http://www.simpletest.org/>
// this path is *NOT* universal, but deployed on the bootcamp-coders server
require_once("/usr/lib/php5/simpletest/autorun.php");

// next, require the classes need to test the project under scrutiny
require_once("../php/classes/checkout.php");
require_once("../php/classes/user.php");
require_once("../php/classes/profile.php");
require_once("../php/classes/order.php");

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

/**
 * Unit test for the checkout class
 *
 * This is a SimpleTest test case for the CRUD methods of the checkout class.
 *
 * @see checkout
 * @author Jay Renteria <jay@jayrenteria.com>
 **/
class CheckoutTest extends UnitTestCase {
	/**
	 * mysqli object shared amongst all tests
	 **/
	private $mysqli = null;

	/**
	 * instance of the object we are testing with
	 **/
	private $checkout = null;
	private $checkout2 = null;

		// this section contains member variables with constants needed for creating a new checkout
	/**
	 * order id of the test check out

	 **/
	private $order = null;
	private $orderDate = null;
	private $profile = null;
	private $user = null;

	private $order2 = null;
	private $profile2 = null;
	private $user2 = null;

	/**
	 * date the checkout was created
	 **/
	private $checkoutDate = null;

	/**
	 * sets up the mySQL connection for this test
	 **/
	public function setUp() {
		// first, connect to mysqli
		mysqli_report(MYSQLI_REPORT_STRICT);
		$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
		$this->mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

		// second, create an instance of the object under scrutiny
		//NEED TO BUILD AN INSTANCE OF THIS OBJECT
		$this->checkoutDate = new DateTime();

		$this->user = new User(null, "test@test.com", 'AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB0BC99AB10BC99AC99AB0BC99AB10BC99AB10BC99AB1010', '99AB10BC99AB10BC99AB10BC99AB10BC', '99AB10BC99AB10BC');
		$this->user->insert($this->mysqli);

		$this->profile = new Profile(null, 'toto', 'sinatra', '505 986700798', 'm', 'kj', 'images/toto.jpg', $this->user->getUserId());
		$this->profile->insert($this->mysqli);

		$this->orderDate = new DateTime();
		$this->order = new Order(null, $this->profile->getProfileId(), $this->orderDate);
		$this->order->insert($this->mysqli);

		$this->checkout = new Checkout(null, $this->order->getOrderId(), $this->checkoutDate);

		$this->user2 = new User(null, "test2@test.com", 'Aa10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB0BC99AB10BC99AC99AB0BC99AB10BC99AB10BC99AB1010', '99Aa10BC99AB10BC99AB10BC99AB10BC', '99Aa10BC99AB10BC');
		$this->user2->insert($this->mysqli);

		$this->profile2 = new Profile(null, $this->profile->getFirstName(), $this->profile->getLastName(),
			$this->profile->getPhone(), $this->profile->getProfileType(), $this->profile->getCustomerToken(),
			$this->profile->getImagePath(), $this->user2->getUserId());
		$this->profile2->insert($this->mysqli);

		$this->order2 = new Order(null, $this->profile2->getProfileId(), $this->order->getOrderDate());
		$this->order2->insert($this->mysqli);

		$this->checkout2 = new Checkout(null, $this->order2->getOrderId(), $this->checkout->getCheckoutDate());

		$this->assertNotNull($this->user2);
		$this->assertNotNull($this->profile2);
		$this->assertNotNull($this->order2);
		$this->assertNotNull($this->checkout2);
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->profile);
		$this->assertNotNull($this->mysqli);
	}

	/**
	 * tears down the connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		// destroy the object if it was created
		if($this->checkout2 !== null && $this->checkout2->getCheckoutId() !== null) {
			$this->checkout2->delete($this->mysqli);
		}
		$this->checkout2 = null;

		if($this->checkout !== null && $this->checkout->getCheckoutId() !== null) {
			$this->checkout->delete($this->mysqli);
		}
		$this->checkout = null;

		if($this->order2 !== null) {
			$this->order2->delete($this->mysqli);
			$this->order2 = null;
		}

		if($this->order !== null) {
			$this->order->delete($this->mysqli);
			$this->order = null;
		}

		if($this->profile2 !== null) {
			$this->profile2->delete($this->mysqli);
			$this->profile2 = null;
		}

		if($this->profile !== null) {
			$this->profile->delete($this->mysqli);
			$this->profile = null;
		}

		if($this->user2 !== null) {
			$this->user2->delete($this->mysqli);
			$this->user2 = null;
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
	 * test inserting a valid checkout into mySQL
	 **/
	public function testInsertValidCheckout() {
		// zeroth, ensure the checkout and mySQL class are sane
		$this->assertNotNull($this->checkout);
		$this->assertNotNull($this->mysqli);

		// first, insert the checkout into mySQL
		$this->checkout->insert($this->mysqli);

		// second, grab a checkout from mySQL
		$mysqlCheckout = Checkout::getCheckoutByCheckoutId($this->mysqli, $this->checkout->getCheckoutId());

		// third, assert the checkout we have created and mySQL's checkout are the same object
		$this->assertIdentical($this->checkout->getCheckoutId(), $mysqlCheckout->getCheckoutId());
		$this->assertIdentical($this->checkout->getOrderId(), $mysqlCheckout->getOrderId());
		$this->assertIdentical($this->checkout->getCheckoutDate(), $mysqlCheckout->getCheckoutDate());
	}

	/**
	 * test inserting an invalid checkout into mySQL
	 **/
	public function testInsertInvalidCheckout() {
		// zeroth, ensure the checkout and mySQL class are sane
		$this->assertNotNull($this->checkout);
		$this->assertNotNull($this->mysqli);

		// first, set the checkout id to an invented value that should never insert in the first place
		$this->checkout->setCheckoutId(42);

		// second, try to insert the checkout and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->checkout->insert($this->mysqli);

		// third, set the checkout to null to prevent tearDown() from deleting a checkout that never existed
		$this->checkout = null;
	}

	/**
	 * test deleting a checkout from mySQL
	 **/
	public function testDeleteValidCheckout() {
		// zeroth, ensure the checkout and mySQL class are sane
		$this->assertNotNull($this->checkout);
		$this->assertNotNull($this->mysqli);

		// first, assert the checkout is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->checkout->insert($this->mysqli);
		$mysqlCheckout = Checkout::getCheckoutByCheckoutId($this->mysqli, $this->checkout->getCheckoutId());
		$this->assertIdentical($this->checkout->getCheckoutId(), $mysqlCheckout->getCheckoutId());

		// second, delete the checkout from mySQL and re-grab it from mySQL and assert it does not exist
		$this->checkout->delete($this->mysqli);
		$mysqlCheckout = Checkout::getCheckoutByCheckoutId($this->mysqli, $this->checkout->getCheckoutId());
		$this->assertNull($mysqlCheckout);

		// third, set the checkout to null to prevent tearDown() from deleting a checkout that has already been deleted
		$this->checkout = null;
	}

	/**
	 * test deleting a checkout from mySQL that does not exist
	 **/
	public function testDeleteInvalidCheckout() {
		// zeroth, ensure the checkout and mySQL class are sane
		$this->assertNotNull($this->checkout);
		$this->assertNotNull($this->mysqli);

		// first, try to delete the checkout before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->checkout->delete($this->mysqli);

		// second, set the checkout to null to prevent tearDown() from deleting a checkout that has already been deleted
		$this->checkout = null;
	}

	/**
	 * test updating a checkout from mySQL
	 **/
	public function testUpdateValidCheckout() {
		// zeroth, ensure the checkout and mySQL class are sane
		$this->assertNotNull($this->checkout);
		$this->assertNotNull($this->mysqli);

		// first, assert the checkout is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->checkout->insert($this->mysqli);
		$mysqlCheckout = Checkout::getCheckoutByCheckoutId($this->mysqli, $this->checkout->getCheckoutId());
		$this->assertIdentical($this->checkout->getCheckoutId(), $mysqlCheckout->getCheckoutId());
//
		// second, change the checkout, update it mySQL
		$newDate = $this->checkout->getCheckoutDate();
		$this->checkout->setCheckoutDate($newDate);
		$this->checkout->update($this->mysqli);

		// third, re-grab the checkout from mySQL
		$mysqlCheckout = Checkout::getCheckoutByCheckoutId($this->mysqli, $this->checkout->getCheckoutId());
		$this->assertNotNull($mysqlCheckout);

		// fourth, assert the checkout we have updated and mySQL's checkout are the same object
		$this->assertIdentical($this->checkout->getCheckoutId(), $mysqlCheckout->getCheckoutId());
		$this->assertIdentical($this->checkout->getOrderId(), $mysqlCheckout->getOrderId());
		$this->assertIdentical($this->checkout->getCheckoutDate(), $mysqlCheckout->getCheckoutDate());
	}

	/**
	 * test updating a checkout from mySQL that does not exist
	 **/
	public function testUpdateInvalidCheckout() {
		// zeroth, ensure the checkout and mySQL class are sane
		$this->assertNotNull($this->checkout);
		$this->assertNotNull($this->mysqli);

		// first, try to update the checkout before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->checkout->update($this->mysqli);

		// second, set the checkout to null to prevent tearDown() from deleting a checkout that has already been deleted
		$this->checkout = null;
	}

	/**
	 * test get valid checkout by checkout date
	 */
	public function testGetValidCheckoutByCheckoutDate() {
		$this->assertNotNull($this->checkout);
		$this->assertNotNull($this->mysqli);

		$this->checkout->insert($this->mysqli);
		$formattedDate = $this->checkout->getCheckoutDate()->format("Y-m-d H:i:s");
		$mysqlCheckouts = Checkout::getCheckoutByCheckoutDate($this->mysqli, $formattedDate);

		foreach($mysqlCheckouts as $mysqlCheckout) {
			$this->assertNotNull($mysqlCheckout->getOrderId());
			$this->assertTrue($mysqlCheckout->getOrderId() > 0);
			$this->assertIdentical($this->checkout->getCheckoutId(), $mysqlCheckout->getCheckoutId());
			$this->assertIdentical($this->checkout2->getCheckoutId(), $mysqlCheckout->getCheckoutId());
		}
	}

	/**
	 * test get invalid checkout by checkout date
	 */
	public function testGetInvalidCheckoutByCheckoutDate() {
		$this->assertNotNull($this->checkout);
		$this->assertNotNull($this->mysqli);

		$this->checkout->insert($this->mysqli);
		$formattedDate = "2015-02-05 12:38:34";
		$mysqlCheckout = Checkout::getCheckoutByCheckoutDate($this->mysqli, $formattedDate);

		$this->assertNull($mysqlCheckout);
	}
}
?>
