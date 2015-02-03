<?php
// first, require the SimpleTest framework <http://www.simpletest.org/>
// this path is *NOT* universal, but deployed on the bootcamp-coders server
require_once("/usr/lib/php5/simpletest/autorun.php");

// next, require the class from the project under scrutiny
require_once("../php/classes/category.php");

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

/**
 * unit test for the Category class
 *
 * This is a simpletest test case for the CRUD methods of the Category class
 *
 * @see category
 * @author Jay Renteria <jason@kingscreations.org>
 *
 **/

class CategoryTest extends UnitTestCase {
	/**
	 * mysqli object shared amongst all tests
	 **/
	private $mysqli = null;
	/**
	 * instance of the object we are testing with
	 **/
	private $category = null;

	// this section contains member variables with constants needed for creating a new category
	/**
	 * category name of the test category
	 **/
	private $categoryName = "test category";

	/**
	 * sets up the mySQL connection for this test
	 **/
	public function setUp() {
		// first connect to mysqli
		mysqli_report(MYSQLI_REPORT_STRICT);
		$configArray = readConfig("/etc/apache2/capstone-mysql/farmtoyou.ini");
		$this->mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

		// second create an instance of the object under scrutiny
		$this->category = new Category(null, $this->categoryName);
	}

	/**
	 * tears down the connection to mysql and deletes the test instance object
	 **/
	public function tearDown() {
		if($this->category !== null) {
			$this->category->delete($this->mysqli);
			$this->category = null;
		}

		// disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}


	/**
	 * test inserting a valid category into mySQL
	 **/
	public function testInsertValidCategory() {
		// zeroth, ensure that the category and mySQL class are sane
		$this->assertNotNull($this->category);
		$this->assertNotNull($this->mysqli);

		// first insert the category into mySQL
		$this->category->insert($this->mysqli);

		// second, grab a category from mySQL
		$mysqlCategory = Category::getCategoryByCategoryId($this->mysqli, $this->category->getCategoryId());

		// third, assert the category we have created and mySQL's category are the same object
		$this->assertIdentical($this->category->getCategoryId(), $mysqlCategory->getCategoryId());
		$this->assertIdentical($this->category->getCategoryName(), $mysqlCategory->getCategoryName());
	}

	/**
	 * test inserting an invalid category into mySQL
	 **/
	public function testInsertInvalidCategory(){
		// zeroth, ensure that the category and mySQL class are sane
		$this->assertNotNull($this->category);
		$this->assertNotNull($this->mysqli);

		// first, ser the category id to an invented value that should never insert in the first place
		$this->category->setCategoryId(42);

		// second, try to insert the category and ensure the execption is thrown
		$this->expectException("mysqli_sql_exception");
		$this->category->insert($this->mysqli);

		// third, set the category to null to prevent tearDown() from deleting a category that never existed
		$this->category = null;
	}

	/**
	 * test deleting a category in mySQL
	 **/
	public function testDeleteValidCategory(){
		// zeroth, ensure that the category and mySQL class are sane
		$this->assertNotNull($this->category);
		$this->assertNotNull($this->mysqli);

		// first assert the category is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->category->insert($this->mysqli);
		$mysqliCategory = Category::getCategoryByCategoryId($this->mysqli, $this->category->getCategoryId());
		$this->assertIdentical($this->category->getCategoryId(), $mysqliCategory->getCategoryId());

		// second delete the category from mySQL and re-grab it from mySQL and assert it does not exist
		$this->category->delete($this->mysqli);
		$mysqliCategory = Category::getCategoryByCategoryId($this->mysqli, $this->category->getCategoryId());
		$this->assertNull($mysqliCategory);

		// third set the category to null to prevent tearDown() from deleting a category that has already been deleted
		$this->category = null;
	}

	/**
	 * test deleting a category from mySQL that does not exist
	 **/
	public function testDeleteInvalidCategory(){
		// zeroth, ensure that the category and mySQL class are sane
		$this->assertNotNull($this->category);
		$this->assertNotNull($this->mysqli);

		// first try to delete the category before inserting it and ensure that the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->category->delete($this->mysqli);

		// second set the category to null to prevent tearDown() from deleting a category that has already been deleted
		$this->category = null;
	}

	/**
	 * test updating a category from mySQL
	 **/
	public function testUpdateValidCategory(){
		// zeroth, ensure that the category and mySQL class are sane
		$this->assertNotNull($this->category);
		$this->assertNotNull($this->mysqli);

		// first asser the category is inserting into mySQL by grabbing it from mySQL and asserting the primary key
		$this->category->insert($this->mysqli);
		$mysqlCategory = Category::getCategoryByCategoryId($this->mysqli, $this->category->getCategoryId());
		$this->assertIdentical($this->category->getCategoryId(), $mysqlCategory ->getCategoryId());

		// second change the category and update it in mySQL
		$newCategoryName = "test update category";
		$this->category->setCategoryName($newCategoryName);
		$this->category->update($this->mysqli);

		// third, re-grab the category form mySQL
		$mysqlCategory = Category::getCategoryByCategoryId($this->mysqli, $this->category->getCategoryId());
		$this->assertNotNull($mysqlCategory);

		// fourth, assert the category we have updated and mySQL's category are the same object
		$this->assertIdentical($this->category->getCategoryId(), $mysqlCategory->getCategoryId());
		$this->assertIdentical($this->category->getCategoryName(), $mysqlCategory->getCategoryName());
	}

	/**
	 * test updating a category from mySQL that does not exists
	 **/
	public function testUpdateInvalidCategory(){
		// zeroth, ensure that the category and mySQL class are sane
		$this->assertNotNull($this->category);
		$this->assertNotNull($this->mysqli);

		// first try to update the category before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->category->update($this->mysqli);

		// second set the comment to null to prevent tearDown() from deleting a comment that has already been deleted
		$this->category= null;
	}

	// add test for category name, need to add get by category name in category.php
}
