<?php
// first, require the SimpleTest framework <http://www.simpletest.org/>
require_once("/usr/lib/php5/simpletest/autorun.php");

// the class to test
require_once("../php/classes/product.php");

// require the encrypted configuration functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");


/**
 * Unit test for the Product class
 *
 * This is a SimpleTest test case for the CRUD methods of the Product class.
 *
 * @see Product
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
class ProductTest extends UnitTestCase {
	/**
	 * mysqli object shared amongst all tests
	 **/
	private $mysqli = null;
	/**
	 * instance of the object we are testing with
	 **/
	private $product = null;

	// this section contains member variables with constants needed for creating a new product
	/**
	 * @var int $profileId id for the profile. This is a foreign key to the profile entity.
	 */
	private $profileId;

	/**
	 * @var string $imagePath image path of the product
	 */
	private $imagePath;

	/**
	 * @var string $productName name of the product
	 */
	private $productName;

	/**
	 * @var float $productPrice price of the product
	 */
	private $productPrice;

	/**
	 * @var string $productType type of the product
	 */
	private $productType;

	/**
	 * @var float $productWeight weight of the product
	 */
	private $productWeight;

	/**
	 * sets up the mySQL connection for this test
	 **/
	public function setUp() {
		// get the credentials information from the server
		$configFile = "/etc/apache2/capstone-mysql/farm-to-you.ini";
		$configArray = readConfig($configFile);

		// connection
		mysqli_report(MYSQLI_REPORT_STRICT);
		$this->mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
			$configArray["database"]);

		// instance of product
		$this->product = new Product(null, $this->profileId, $this->imagePath, $this->productName, $this->productPrice,
			$this->productType, $this->productWeight);
	}

	/**
	 * tears down the connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		// destroy the object if it was created
		if($this->product !== null) {
			$this->product->delete($this->mysqli);
			$this->product = null;
		}

		// disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}

	/**
	 * test inserting a valid Product into mySQL
	 **/
	public function testInsertValidProduct() {
		// zeroth, ensure the Product and mySQL class are sane
		$this->assertNotNull($this->product);
		$this->assertNotNull($this->mysqli);

		// first, insert the Product into mySQL
		$this->product->insert($this->mysqli);

		// second, grab a Product from mySQL
		$mysqlProduct = Product::getProductByProductId($this->mysqli, $this->product->getProductId());

		// third, assert the Product we have created and mySQL's Product are the same object
		$this->assertIdentical($this->product->getProductId(), $mysqlProduct->getProductId());
		$this->assertIdentical($this->product->getProfileId(), $mysqlProduct->getProfileId());
		$this->assertIdentical($this->product->getImagePath(), $mysqlProduct->getImagePath());
		$this->assertIdentical($this->product->getProductName(), $mysqlProduct->getProductName());
		$this->assertIdentical($this->product->getProductPrice(), $mysqlProduct->getProductPrice());
		$this->assertIdentical($this->product->getProductType(), $mysqlProduct->getProductType());
		$this->assertIdentical($this->product->getProductWeight(), $mysqlProduct->getProductWeight());
	}

	/**
	 * test inserting an invalid Product into mySQL
	 **/
	public function testInsertInvalidProduct() {
		$this->assertNotNull($this->product);
		$this->assertNotNull($this->mysqli);

		// first, set the product id to an invented value that should never insert in the first place
		$this->product->setProductId(42);

		// second, try to insert the Product and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->product->insert($this->mysqli);

		// third, set the Product to null to prevent tearDown() from deleting a Tweet that never existed
		$this->product = null;
	}

	/**
	 * test deleting a Product from mySQL
	 **/
	public function testDeleteValidProduct() {
		$this->assertNotNull($this->product);
		$this->assertNotNull($this->mysqli);

		// first, assert the Product is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->product->insert($this->mysqli);
		$mysqlProduct = Product::getProductByProductId($this->mysqli, $this->product->getProductId());
		$this->assertIdentical($this->product->getProductId(), $mysqlProduct->getProductId());

		// second, delete the Product from mySQL and re-grab it from mySQL and assert it does not exist
		$this->product->delete($this->mysqli);
		$mysqlProduct = Product::getProductByProductId($this->mysqli, $this->product->getProductId());
		$this->assertNull($mysqlProduct);

		// third, set the Product to null to prevent tearDown() from deleting a Product that has already been deleted
		$this->product = null;
	}

	/**
	 * test deleting a Product from mySQL that does not exist
	 **/
	public function testDeleteInvalidProduct() {
		$this->assertNotNull($this->product);
		$this->assertNotNull($this->mysqli);

		// first, try to delete the Product before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->product->delete($this->mysqli);

		$this->product = null;
	}

	/**
	 * test updating a Product from mySQL
	 **/
	public function testUpdateValidProduct() {
		$this->assertNotNull($this->product);
		$this->assertNotNull($this->mysqli);

		$this->product->insert($this->mysqli);
		$mysqlProduct = Product::getProductByProductId($this->mysqli, $this->product->getProductId());
		$this->assertIdentical($this->product->getProductId(), $mysqlProduct->getProductId());

		// second, change the Product, update it mySQL
		$newName = "new product name!";
		$this->product->setProductName($newName);
		$this->product->update($this->mysqli);

		$mysqlProduct = Product::getProductByProductId($this->mysqli, $this->product->getProductId());
		$this->assertNotNull($mysqlProduct);

		// fourth, assert the Product we have updated and mySQL's Product are the same object
		$this->assertIdentical($this->product->getProductId(), $mysqlProduct->getProductId());
		$this->assertIdentical($this->product->getProfileId(), $mysqlProduct->getProfileId());
		$this->assertIdentical($this->product->getImagePath(), $mysqlProduct->getImagePath());
		$this->assertIdentical($this->product->getProductName(), $mysqlProduct->getProductName());
		$this->assertIdentical($this->product->getProductPrice(), $mysqlProduct->getProductPrice());
		$this->assertIdentical($this->product->getProductType(), $mysqlProduct->getProductType());
		$this->assertIdentical($this->product->getProductWeight(), $mysqlProduct->getProductWeight());
	}

	/**
	 * test updating a Product from mySQL that does not exist
	 **/
	public function testUpdateInvalidProduct() {
		$this->assertNotNull($this->product);
		$this->assertNotNull($this->mysqli);

		// first, try to update the Product before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->product->update($this->mysqli);

		// second, set the Product to null to prevent tearDown() from deleting a Product that has already been deleted
		$this->product = null;
	}
}
?>