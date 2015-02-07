<?php
// first, require the SimpleTest framework <http://www.simpletest.org/>
require_once("/usr/lib/php5/simpletest/autorun.php");

// the classes to test
require_once("../php/classes/user.php");
require_once("../php/classes/profile.php");
require_once("../php/classes/product.php");
require_once("../php/classes/order.php");
require_once("../php/classes/orderproduct.php");

// require the encrypted configuration functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");


/**
 * Unit test for the OrderProduct class
 *
 * This is a SimpleTest test case for the CRUD methods of the OrderProduct class.
 *
 * @see OrderProduct
 * @author Florian Goussin <fgoussin@cnm.edu>
 **/
class OrderProductTest extends UnitTestCase {
	/**
	 * mysqli object shared amongst all tests
	 **/
	private $mysqli = null;

	/**
	 * instance of the object we are testing with
	 **/
	private $orderProduct = null;

	/**
	 * instance of the first user (profile foreign key)
	 **/
	private $user = null;

	/**
	 * instance of the first profile (order foreign key)
	 **/
	private $profile = null;

	/**
	 * instance of the object we are testing with
	 **/
	private $product = null;

	/**
	 * instance of the object we are testing with
	 */
	private $order = null;

	/**
	 * @var int $productQuantity how many products for this order
	 */
	private $productQuantity = 5;

	/**
	 * @var string $imagePath image path of the product
	 */
	private $imagePath = "images/tomato.jpg";

	/**
	 * @var string $productName name of the product
	 */
	private $productName = "cherry tomatoes";

	/**
	 * @var float $productPrice price of the product
	 */
	private $productPrice = 5.6;

	/**
	 * @var string $productType type of the product
	 */
	private $productType = "vegetable";

	/**
	 * @var float $productWeight weight of the product
	 */
	private $productWeight = 1.2;

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

		// instances for the foreign keys
		$this->user = new User(null, "test@test.com", 'AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB10BC99AB0BC99AB10BC99AC99AB0BC99AB10BC99AB10BC99AB1010', '99AB10BC99AB10BC99AB10BC99AB10BC', '99AB10BC99AB10BC');
		$this->user->insert($this->mysqli);

		$this->profile = new Profile(null, 'toto', 'sinatra', '505 986700798', 'm', 'kj', 'images/toto.jpg',
			$this->user->getUserId());
		$this->profile->insert($this->mysqli);

		$this->product = new Product(null, $this->profile->getProfileId(), $this->imagePath, $this->productName, $this->productPrice,
			$this->productType, $this->productWeight);
		$this->product->insert($this->mysqli);

		$this->orderDate = new DateTime();
		$this->order = new Order(null, $this->profile->getProfileId(), $this->orderDate);
		$this->order->insert($this->mysqli);

		// instance of orderProduct
		$this->orderProductDate = new DateTime();
		$this->orderProduct = new OrderProduct($this->order->getOrderId(), $this->product->getProductId(), $this->productQuantity);
	}

	/**
	 * tears down the connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		if($this->orderProduct !== null) {
			$this->orderProduct->delete($this->mysqli);
		}
		$this->orderProduct = null;

		if($this->order !== null) {
			$this->order->delete($this->mysqli);
			$this->order = null;
		}

		if($this->product !== null) {
			$this->product->delete($this->mysqli);
			$this->product = null;
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
	 * test get valid order product by order id and by product id
	 */
	public function testGetValidOrderProductByOrderIdAndProductId() {
		$this->assertNotNull($this->orderProduct);
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->profile);
		$this->assertNotNull($this->order);
		$this->assertNotNull($this->product);
		$this->assertNotNull($this->mysqli);

		// first, insert the Order into mySQL
		$this->orderProduct->insert($this->mysqli);

		// second, grab a Order from mySQL
		$mysqlOrderProduct = OrderProduct::getOrderProductByOrderIdAndProductId($this->mysqli, $this->order->getOrderId(),
			$this->product->getProductId());

		// third, assert the Order we have created and mySQL's Order are the same object
		$this->assertIdentical($this->orderProduct->getProductId(), $mysqlOrderProduct->getProductId());
	}

	/**
	 * test get invalid order product by order id and by product id
	 */
	public function testGetInvalidOrderProductByOrderIdAndProductId() {
		$this->assertNotNull($this->orderProduct);
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->profile);
		$this->assertNotNull($this->order);
		$this->assertNotNull($this->product);
		$this->assertNotNull($this->mysqli);

		// first, insert the Order into mySQL
		$this->orderProduct->insert($this->mysqli);

		// second, grab a Order from mySQL
		$mysqlOrderProduct = OrderProduct::getOrderProductByOrderIdAndProductId($this->mysqli, 56,
			$this->product->getProductId());

		// third, assert the Order we have created and mySQL's Order are the same object
		$this->assertNull($mysqlOrderProduct);
	}

	/**
	 * test get invalid order product by order id and by product id
	 */
	public function testValidInsertOrderProduct() {
		$this->assertNotNull($this->orderProduct);
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->profile);
		$this->assertNotNull($this->order);
		$this->assertNotNull($this->product);
		$this->assertNotNull($this->mysqli);

		$this->orderProduct->insert($this->mysqli);

		$mysqlOrderProduct = OrderProduct::getOrderProductByOrderIdAndProductId($this->mysqli, $this->order->getOrderId(),
			$this->product->getProductId());

		$this->assertIdentical($this->order->getOrderId(), $mysqlOrderProduct->getOrderId());
		$this->assertIdentical($this->product->getProductId(), $mysqlOrderProduct->getProductId());
	}

	/**
	 * test get invalid order product by order id and by product id
	 */
	public function testInvalidInsertOrderProduct() {
		$this->assertNotNull($this->orderProduct);
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->profile);
		$this->assertNotNull($this->order);
		$this->assertNotNull($this->product);
		$this->assertNotNull($this->mysqli);

		$this->orderProduct->insert($this->mysqli);

		$mysqlOrderProduct = OrderProduct::getOrderProductByOrderIdAndProductId($this->mysqli, 56,
			$this->product->getProductId());

		$this->assertNull($mysqlOrderProduct);
	}

	/**
	 * test get invalid order product by order id and by product id
	 */
	public function testValidDeleteOrderProduct() {
		$this->assertNotNull($this->orderProduct);
		$this->assertNotNull($this->user);
		$this->assertNotNull($this->profile);
		$this->assertNotNull($this->order);
		$this->assertNotNull($this->product);
		$this->assertNotNull($this->mysqli);

		// first, assert the Product is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->orderProduct->insert($this->mysqli);
		$mysqlOrderProduct = OrderProduct::getOrderProductByOrderIdAndProductId($this->mysqli,
			$this->order->getOrderId(), $this->product->getProductId());

		$this->assertIdentical($this->orderProduct->getProductId(), $mysqlOrderProduct->getProductId());

		// second, delete the OrderProduct from mySQL and re-grab it from mySQL and assert it does not exist
		$this->orderProduct->delete($this->mysqli);
		$mysqlOrderProduct = OrderProduct::getOrderProductByOrderIdAndProductId($this->mysqli,
			$this->order->getOrderId(), $this->product->getProductId());
		$this->assertNull($mysqlOrderProduct);

		// third, set the Product to null to prevent tearDown() from deleting a Product that has already been deleted
		$this->orderProduct = null;
	}
}
?>