<?php
// first, require the SimpleTest framework <http://www.simpletest.org/>
require_once("/usr/lib/php5/simpletest/autorun.php");

// the class to test
require_once("../php/classes/orderProduct.php");

// require the encrypted configuration functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");


/**
 * Unit test for the OrderProduct class
 *
 * This is a SimpleTest test case for the CRUD methods of the OrderProduct class.
 *
 * @see OrderProduct
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
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
	 * @var int $orderId the id of the order. Foreign Key to the order entity
	 */
	private $orderId;

	/**
	 * @var int $productId the id of the product. Foreign Key to the product entity
	 */
	private $productId;

	/**
	 * @var int $productQuantity how many products for this order
	 */
	private $productQuantity;

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

		// instance of orderProduct
		$this->orderProductDate = new DateTime();
		$this->orderProduct = new OrderProduct(null, $this->profileId, $this->orderProductDate);
	}

	/**
	 * tears down the connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		// destroy the object if it was created
		if($this->orderProduct !== null) {
			$this->orderProduct->delete($this->mysqli);
			$this->orderProduct = null;
		}

		// disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}


}
?>