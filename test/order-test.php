<?php
// first, require the SimpleTest framework <http://www.simpletest.org/>
require_once("/usr/lib/php5/simpletest/autorun.php");

// the classes to test
require_once("../php/classes/user.php");
require_once("../php/classes/profile.php");
require_once("../php/classes/order.php");

// require the encrypted configuration functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");


/**
 * Unit test for the Order class
 *
 * This is a SimpleTest test case for the CRUD methods of the Order class.
 *
 * @see Order
 * @author Florian Goussin <fgoussin@cnm.edu>
 **/
class OrderTest extends UnitTestCase {
	/**
	 * mysqli object shared amongst all tests
	 **/
	private $mysqli = null;

	/**
	 * instance of the first user (profile foreign key)
	 **/
	private $user = null;

	/**
	 * instance of the first profile (order foreign key)
	 **/
	private $profile = null;

	/**
	 * instance of the first order
	 **/
	private $order = null;

	/**
	 * instance of the second user (profile foreign key)
	 **/
	private $user2 = null;

	/**
	 * instance of the second profile (order foreign key)
	 **/
	private $profile2 = null;

	/**
	 * instance of the second order
	 **/
	private $order2 = null;

	/**
	 * @var int $profileId id for the profile. This is a foreign key to the profile entity.
	 */
	private $profileId = 1;

	/**
	 * @var string $orderDate name of the order
	 */
	private $orderDate = null;
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

		//instances for the foreign keys
		$this->user = new User(null, "test@test.com", 'AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB0BC99AB10BC99AC99AB0BC99AB10BC99AB10BC99AB1010', '99AB10BC99AB10BC99AB10BC99AB10BC', '99AB10BC99AB10BC');
		$this->user->insert($this->mysqli);

		$this->profile = new Profile(null, 'toto', 'sinatra', '505 986700798', 'm', 'kj', 'images/toto.jpg',
			$this->user->getUserId());
		$this->profile->insert($this->mysqli);

		$this->orderDate = new DateTime();
		$this->order = new Order(null, $this->profile->getProfileId(), $this->orderDate);

		// same for a second order
		$this->user2 = new User(null, "test@test.com", 'AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB0BC99AB10BC99AC99AB0BC99AB10BC99AB10BC99AB1010', '99AB10BC99AB10BC99AB10BC99AB10BC', '99AB10BC99AB10BC');
		$this->user2->insert($this->mysqli);

		$this->profile2 = new Profile(null, $this->profile->getFirstName(), $this->profile->getLastName(),
			$this->profile->getPhone(), $this->profile->getProfileType(), $this->profile->getCustomerToken(),
			$this->profile->getImagePath(), $this->user2->getUserId());
		$this->profile2->insert($this->mysqli);

		$this->order2 = new Order(null, $this->profile2->getProfileId(), $this->order->getOrderDate());

		$this->assertNotNull($this->user2);
		$this->assertNotNull($this->profile2);
		$this->assertNotNull($this->order2);
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->profile);
		$this->assertNotNull($this->mysqli);
	}

	/**
	 * tears down the connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		if($this->order2 !== null && $this->order2->getOrderId() !== null) {
			$this->order2->delete($this->mysqli);
			$this->order2 = null;
		}

		if($this->order !== null && $this->order->getOrderId() !== null) {
			$this->order->delete($this->mysqli);
			$this->order = null;
		}

		if($this->profile2 !== null && $this->profile2->getProfileId() !== null) {
			$this->profile2->delete($this->mysqli);
			$this->profile2 = null;
		}

		if($this->profile !== null && $this->profile->getProfileId() !== null) {
			$this->profile->delete($this->mysqli);
			$this->profile = null;
		}

		if($this->user2 !== null && $this->user2->getUserId() !== null) {
			$this->user2->delete($this->mysqli);
			$this->user2 = null;
		}

		if($this->user !== null && $this->user->getUserId() !== null) {
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
	 * test inserting a valid Order into mySQL
	 **/
	public function testInsertValidOrder() {
		// zeroth, ensure the Order and mySQL class are sane
		$this->assertNotNull($this->order);
		$this->assertNotNull($this->mysqli);

		// first, insert the Order into mySQL
		$this->order->insert($this->mysqli);

		// second, grab a Order from mySQL
		$mysqlOrder = Order::getOrderByOrderId($this->mysqli, $this->order->getOrderId());

		// third, assert the Order we have created and mySQL's Order are the same object
		$this->assertIdentical($this->order->getOrderId(), $mysqlOrder->getOrderId());
		$this->assertIdentical($this->order->getProfileId(), $mysqlOrder->getProfileId());
		$this->assertIdentical($this->order->getOrderDate(), $mysqlOrder->getOrderDate());
	}

	/**
	 * test inserting an invalid Order into mySQL
	 **/
	public function testInsertInvalidOrder() {
		$this->assertNotNull($this->order);
		$this->assertNotNull($this->mysqli);

		// first, set the order id to an invented value that should never insert in the first place
		$this->order->setOrderId(42);

		// second, try to insert the Order and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->order->insert($this->mysqli);

		// third, set the Order to null to prevent tearDown() from deleting a Tweet that never existed
		$this->order = null;
	}

	/**
	 * test deleting a Order from mySQL
	 **/
	public function testDeleteValidOrder() {
		$this->assertNotNull($this->order);
		$this->assertNotNull($this->mysqli);

		// first, assert the Order is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->order->insert($this->mysqli);
		$mysqlOrder = Order::getOrderByOrderId($this->mysqli, $this->order->getOrderId());
		$this->assertIdentical($this->order->getOrderId(), $mysqlOrder->getOrderId());

		// second, delete the Order from mySQL and re-grab it from mySQL and assert it does not exist
		$this->order->delete($this->mysqli);
		$mysqlOrder = Order::getOrderByOrderId($this->mysqli, $this->order->getOrderId());
		$this->assertNull($mysqlOrder);

		// third, set the Order to null to prevent tearDown() from deleting a Order that has already been deleted
		$this->order = null;
	}

	/**
	 * test deleting a Order from mySQL that does not exist
	 **/
	public function testDeleteInvalidOrder() {
		$this->assertNotNull($this->order);
		$this->assertNotNull($this->mysqli);

		// first, try to delete the Order before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->order->delete($this->mysqli);

		$this->order = null;
	}

	/**
	 * test updating a Order from mySQL
	 **/
	public function testUpdateValidOrder() {

		$this->order->insert($this->mysqli);
		$mysqlOrder = Order::getOrderByOrderId($this->mysqli, $this->order->getOrderId());
		$this->assertIdentical($this->order->getOrderId(), $mysqlOrder->getOrderId());

		// second, change the Order, update it mySQL
		$newDate = new DateTime();
		$this->order->setOrderDate($newDate);
		$this->order->update($this->mysqli);

		$mysqlOrder = Order::getOrderByOrderId($this->mysqli, $this->order->getOrderId());
		$this->assertNotNull($mysqlOrder);

		// fourth, assert the Order we have updated and mySQL's Order are the same object
		$this->assertIdentical($this->order->getOrderId(), $mysqlOrder->getOrderId());
		$this->assertIdentical($this->order->getProfileId(), $mysqlOrder->getProfileId());
		$this->assertIdentical($this->order->getOrderDate(), $mysqlOrder->getOrderDate());
	}

	/**
	 * test updating a Order from mySQL that does not exist
	 **/
	public function testUpdateInvalidOrder() {
		$this->assertNotNull($this->order);
		$this->assertNotNull($this->mysqli);

		// first, try to update the Order before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->order->update($this->mysqli);

		// second, set the Order to null to prevent tearDown() from deleting a Order that has already been deleted
		$this->order = null;
	}

	/**
	 * test get valid order by order date
	 */
	public function testGetValidOrderByOrderDate() {
		$this->assertNotNull($this->order);
		$this->assertNotNull($this->mysqli);

		$this->order->insert($this->mysqli);
		$formattedDate = $this->order->getOrderDate()->format("Y-m-d H:i:s");
		$mysqlOrders = Order::getOrderByOrderDate($this->mysqli, $formattedDate);

		foreach($mysqlOrders as $mysqlOrder) {
			$this->assertNotNull($mysqlOrder->getOrderId());
			$this->assertTrue($mysqlOrder->getOrderId() > 0);
			$this->assertIdentical($this->order->getOrderId(), $mysqlOrder->getOrderId());
			$this->assertIdentical($this->order2->getOrderId(), $mysqlOrder->getOrderId());
		}
	}

	/**
	 * test get invalid order by order date
	 */
	public function testGetInvalidOrderByOrderDate() {
		$this->assertNotNull($this->order);
		$this->assertNotNull($this->mysqli);

		$this->order->insert($this->mysqli);
		$formattedDate = "2015-02-05 12:38:34";
		$mysqlOrder = Order::getOrderByOrderDate($this->mysqli, $formattedDate);
		$this->assertNull($mysqlOrder);
	}

	/**
	 * test get valid order by order id
	 */
	public function testGetValidOrderByOrderId() {
		$this->assertNotNull($this->order);
		$this->assertNotNull($this->mysqli);

		$this->order->insert($this->mysqli);
		$mysqlOrder = Order::getOrderByOrderId($this->mysqli, $this->order->getOrderId());
		$this->assertIdentical($this->order->getOrderId(), $mysqlOrder->getOrderId());
	}

	/**
	 * test get invalid order by order id
	 */
	public function testGetInvalidOrderByOrderId() {
		$this->assertNotNull($this->order);
		$this->assertNotNull($this->mysqli);

		$this->order->insert($this->mysqli);
		$mysqlOrder = Order::getOrderByOrderId($this->mysqli, 4);
		$this->assertNull($mysqlOrder);
	}
}
?>