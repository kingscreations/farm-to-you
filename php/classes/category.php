<?php
/**
 * This is the class for the category function of farmtoyou
 *
 * This will be used to store the different categories and can be expanded upon as the site grows.
 *
 * @author Jay Renteria <jay@jayrenteria.com>
 **/

class Category {
	/**
	 * id for the category, this is the primary key
	 */
	private $categoryId;

	/**
	 * name of the category
	 **/
	private $categoryName;

	/**
	 * constructor for this category class
	 *
	 * @param int $newCategoryId id of the category
	 * @param string $newCategoryName name of the category
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g. strings too long, negative integers)
	 **/
	public function __construct($newCategoryId, $newCategoryName) {
		try {
			$this->setCategoryId($newCategoryId);
			$this->setCategoryName($newCategoryName);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor method for the categoryId
	 *
	 * @return int value of categoryId
	 **/
	public function getCategoryId() {
		return ($this->categoryId);
	}

	/**
	 * mutator method for categoryId
	 *
	 * @param mixed $newCategoryId new value of $categoryId or null if new object
	 * @throws InvalidArgumentException if the $categoryId is not an integer
	 * @throws RangeException if the $categoryId is not positive
	 **/
	public function setCategoryId($newCategoryId) {
		if($newCategoryId === null) {
			$this->categoryId = null;
			return;
		}
		// verify the category id is valid
		$newCategoryId = filter_var($newCategoryId, FILTER_VALIDATE_INT);
		if($newCategoryId === false) {
			throw(new InvalidArgumentException("category id is not a valid integer"));
		}
		// verify the category id is positive
		if($newCategoryId <= 0) {
			throw(new RangeException("category id is not positive"));
		}
		// convert and store the user id
		$this->categoryId = intval($newCategoryId);
	}

	/**
	 * accessor method for the category name
	 *
	 * @return string value of category name
	 **/
	public function getCategoryName() {
		return ($this->categoryName);
	}

	/**
	 * mutator method for this category name
	 *
	 * @param string $newCategoryName new value of $categoryName
	 * @throws InvalidArgumentException if the $categoryName is not an integer
	 * @throws RangeException if the $categoryName is longer then 20 characters
	 **/
	public function setCategoryName($newCategoryName) {
		// verify the category name is valid and secure
		$newCategoryName = trim($newCategoryName);
		$newCategoryName = filter_var($newCategoryName, FILTER_SANITIZE_STRING);
		if(empty($newCategoryName) === true) {
			throw(new InvalidArgumentException("category name is empty or insecure"));
		}

		// verify the category name is not longer then 20 characters
		if(strlen($newCategoryName) > 20) {
			throw(new RangeException ("category name too long"));
		}

		// convert and store the category name
		$this->categoryName = $newCategoryName;
	}

	/**
	 * inserts this category into mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the categoryId is null (i.e., dont insert a category that already exists)
		if($this->categoryId !== null) {
			throw(new mysqli_sql_exception("this category already exists"));
		}

		// create query template
		$query = "INSERT INTO category(categoryName) VALUES (?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("s",$this->categoryName);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters:"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement"));
		}

		// update the null categoryId with what mysql just gave us
		$this->categoryId = $mysqli->insert_id;
		// clean up the statement
		$statement->close();
	}

	/**
	 * deletes this category from mysql
	 *
	 * @param resource $mysqli pointer to mysql connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the categoryId is not null (i.e., dont delete a category that has not been inserted)
		if($this->categoryId === null) {
			throw(new mysqli_sql_exception("unable to delete a category that does not exist"));
		}

		// create query template
		$query = "DELETE FROM category WHERE categoryId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("i", $this->categoryId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement"));
		}

		// clean up the statement
		$statement->close();
	}

	/**
	 * updates the category in mySQL
	 *
	 * @param resource $mysqli pointer to mysql connection, by reference
	 * @throws mysqli_sql_exception when mysql related errors occur
	 **/
	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the categoryId is not null (i.e., dont update a category that hasnt been inserted)
		if($this->categoryId === null) {
			throw(new mysqli_sql_exception("unable to update a category that does not exist"));
		}

		// create a query template
		$query = "UPDATE category SET categoryName = ? WHERE categoryId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("si", $this->categoryName, $this->categoryId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mysql statement: " . $statement->error));
		}

		// clean up the statement
		$statement->close();
	}

	/**
	 * gets the category by categoryId
	 *
	 * @param resource $mysqli pointer to mysql connection, by reference
	 * @param int $categoryId category id to search for
	 * @return mixed array of categories found, or null if not found
	 * @throws mysqli_sql_exception when mysql related errors occur
	 **/
	public static function getCategoryByCategoryId(&$mysqli, $categoryId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the description before searching
		$categoryId = trim($categoryId);
		$categoryId = filter_var($categoryId, FILTER_VALIDATE_INT);
		// create query template
		$query = "SELECT categoryId, categoryName FROM category WHERE categoryId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the category id to the place holder in the template
		$wasClean = $statement->bind_param("i", $categoryId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement"));
		}

		// get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

		// grab the store from mySQL
		try {
			$category = null;
			$row = $result->fetch_assoc();
			if($row !== null) {
				$category = new Category($row["categoryId"], $row["categoryName"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
		}

		// free up memory and return the result
		$result->free();
		$statement->close();
		return ($category);
	}


	/**
	 * gets the category by category name
	 *
	 * @param resource $mysqli pointer to mysql connection, by reference
	 * @param string $categoryName category name to search for
	 * @return mixed array of categories found, or null if not found
	 * @throws mysqli_sql_exception when mysql related errors occur
	 **/
	public static function getCategoryByCategoryName(&$mysqli, $categoryName) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the description before searching
		$categoryName = trim($categoryName);
		$categoryName = filter_var($categoryName, FILTER_SANITIZE_STRING);

		// create query template
		$query = "SELECT categoryId, categoryName FROM category WHERE categoryName LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the category name to the place holder in the template
		$categoryName = "%$categoryName%";
		$wasClean = $statement->bind_param("s", $categoryName);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement"));
		}

		// get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

		// grab the store from mySQL
		try {
			$category = null;
			$row = $result->fetch_assoc();
			if($row !== null) {
				$category = new Category($row["categoryId"], $row["categoryName"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
		}

		// free up memory and return the result
		$result->free();
		$statement->close();
		return ($category);
	}

	/**
	 * gets all categories
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @return mixed array of categories found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getAllCategories(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// create query template
		$query	 = "SELECT categoryId, categoryName FROM category";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

		// build an array of store
		$categories = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$category	= new Category($row["categoryId"], $row["categoryName"]);
				$categories[] = $category;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if >= 1 result
		$numberOfCategories = count($categories);
		if($numberOfCategories === 0) {
			return(null);
		} else {
			return($categories);
		}
	}
}