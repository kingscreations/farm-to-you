<?php
/**
 * This is the class for the categoryProduct function of farmtoyou
 *
 * @author Jay Renteria <jay@jayrenteria.com>
 **/

class CategoryProduct {
	/**
	 * id for the category, this is part of the composite key
	 */
	private $categoryId;

	/**
	 * id for the product, this is part of the composite key
	 **/
	private $productId;

	/**
	 * constructor for this category product class
	 *
	 *
	 * /**
	 * mutator method for categoryId
	 *
	 * @param int $newCategoryId new value of $categoryId
	 * @throws InvalidArgumentException if the $categoryId is not an integer
	 * @throws RangeException if the $categoryId is not positive
	 **/
	public function setCategoryId($newCategoryId) {
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
	 * accessor method for the product Id
	 *
	 * @return int value of product id
	 **/
	public function getProductId() {
		return ($this->productId);
	}

	/**
	 * mutator method for this product Id
	 *
	 * @param int $newProductId new value of $productId
	 * @throws InvalidArgumentException if the $productId is not an integer
	 * @throws RangeException if the $productId is not positive
	 **/
	public function setProductId($newProductId) {
		// verify the product id is valid
		$newProductId = filter_var($newProductId, FILTER_VALIDATE_INT);
		if($newProductId === false) {
			throw(new InvalidArgumentException("product id is not a valid integer"));
		}
		// verify the user id is positive
		if($newProductId <= 0) {
			throw(new RangeException("product id is not positive"));
		}
		// convert and store the user id
		$this->productId = intval($newProductId);
	}

	/**
	 * inserts this categoryProduct into mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		// enforce the categoryProduct intersection is null (i.e., dont insert a categoryProduct intersection that already exists)
		if($this->categoryId !== null) {
			throw(new mysqli_sql_exception("this category already exists"));
		}
		if($this->productId !== null){
			throw(new mysqli_sql_exception("this product already exists"));
		}

		// create query template
		$query = "INSERT INTO categoryProduct(categoryId, productId) VALUES (?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ii", $this->categoryId, $this->productId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters:"));
		}
		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement"));
		}
		// update the null categoryId and product id with what mysql just gave us
		$this->categoryId = $mysqli->insert_id;
		$this->productId = $mysqli->insert_id;
		// clean up the statement
		$statement->close();
	}

	/**
	 * deletes this categoryProduct intersection from mysql
	 *
	 * @param resource $mysqli pointer to mysql connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		// enforce the categoryId and productId is not null (i.e., dont delete a categoryProduct that has not been inserted)
		if($this->categoryId === null) {
			throw(new mysqli_sql_exception("unable to delete a category that does not exist"));
		}
		if($this->productId === null) {
			throw(new mysqli_sql_exception("unable to delete a category that does not exist"));
		}
		// create query template
		$query = "DELETE FROM categoryProduct WHERE (categoryId = ?, productId = ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		// bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("ii", $this->categoryId, $this->productId);
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

	public static function getCategoryByCategoryId(&$mysqli, $categoryId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "obeject" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		// sanitize the description before searching
		$categoryId = trim($categoryId);
		$categoryId = filter_var($categoryId, FILTER_VALIDATE_INT);
		// create query template
		$query = "SELECT categoryId, productId FROM categoryProduct WHERE categoryId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		// bind the category id to the place holder in the template
		$categoryId = "%$categoryId%";
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
		// build an array of categories
		$categories = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$category = new category($row["categoryId"], $row["categoryName"]);
				$categories[] = $category;
			} catch(Exception $exception) {
				// if the row couldnt be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}
		// count the results in the array and return:
		// 1) null if 0 results
		// 2) a single object if 1 result
		// 3) the entire array if > 1 result
		$numberOfCategories = count($categories);
		if($numberOfCategories === 0) {
			return (null);
		} else if($numberOfCategories === 1) {
			return ($categories[0]);
		} else {
			return ($categories);
		}
	}
}