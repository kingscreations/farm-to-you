<?php
/**
 * This is the class for the checkout function of farmtoyou
 *
 * @author Jay Renteria <jay@jayrenteria.com>
 **/

class Checkout {
	/**
	 * id for the checkout, this is the primary key
	 */
	private $checkoutId;

	/**
	 * id for the order, this is a foreign key
	 **/
	private $orderId;

	/**
	 * constructor for this checkout class
	 *
	 * @param int $newCheckoutId id of the checkout
	 * @param int $newOrderId of the order
	 * @throws InvalidArgumentException it data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g. strings too long, negative integers)
	 **/
	public function __construct($newCheckoutId, $newOrderId = null) {
		try {
			$this->setCheckoutId($newCheckoutId);
			$this->setOrderId($newOrderId);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor method for the checkoutId
	 *
	 * @return int value of checkoutId
	 **/
	public function getCheckoutId() {
		return ($this->checkoutId);
	}

	/**
	 * mutator method for checkoutId
	 *
	 * @param int $newCheckoutId new value of $checkoutId
	 * @throws InvalidArgumentException if the $checkoutId is not an integer
	 * @throws RangeException if the $checkoutId is not positive
	 **/
	public function setCheckoutId($newCheckoutId) {
		// verify the checkout id is valid
		$newCheckoutId = filter_var($newCheckoutId, FILTER_VALIDATE_INT);
		if($newCheckoutId === false) {
			throw(new InvalidArgumentException("checkout id is not a valid integer"));
		}
		// verify the checkout id is positive
		if($newCheckoutId <= 0) {
			throw(new RangeException("checkout id is not positive"));
		}
		// convert and store the user id
		$this->checkoutId = intval($newCheckoutId);
	}

	/**
	 * accessor method for the order Id
	 *
	 * @return int value of order id
	 **/
	public function getOrderId() {
		return ($this->orderId);
	}

	/**
	 * mutator method for this order Id
	 *
	 * @param int $newOrderId new value of $orderId
	 * @throws InvalidArgumentException if the $orderId is not an integer
	 * @throws RangeException if the $orderId is not positive
	 **/
	public function setOrderId($newOrderId) {
		// verify the order id is valid
		$newOrderId = filter_var($newOrderId, FILTER_VALIDATE_INT);
		if($newOrderId === false) {
			throw(new InvalidArgumentException("order id is not a valid integer"));
		}
		// verify the user id is positive
		if($newOrderId <= 0) {
			throw(new RangeException("order id is not positive"));
		}
		// convert and store the user id
		$this->orderId = intval($newOrderId);
	}

	/**
	 * inserts this checlout into mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		// enforce the checkoutId is null (i.e., dont insert a checkout that already exists)
		if($this->checkoutId !== null) {
			throw(new mysqli_sql_exception("this order already exists"));
		}
		// create query template
		$query = "INSERT INTO checkout(checkoutId, orderId) VALUES (?, ?,)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ii", $this->checkoutId, $this->orderId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters:"));
		}
		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement"));
		}
		// update the null checkoutId with what mysql just gave us
		$this->checkoutId = $mysqli->insert_id;
		// clean up the statement
		$statement->close();
	}

	/**
	 * deletes this checkout from mysql
	 *
	 * @param resource $mysqli pointer to mysql connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		// enforce the checkoutId is not null (i.e., dont delete a checkout that has not been inserted)
		if($this->checkoutId === null) {
			throw(new mysqli_sql_exception("unable to delete a checkout that does not exist"));
		}
		// create query template
		$query = "DELETE FROM checkout WHERE checkoutId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		// bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("i", $this->checkoutId);
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
	 * updates the checkout in mySQL
	 *
	 * @param resource $mysqli pointer to mysql connection, by reference
	 * @throws mysqli_sql_exception when mysql related errors occur
	 **/
	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		// enforce the checkoutId is not null (i.e., dont update a checkout that hasnt been inserted)
		if($this->checkoutId === null) {
			throw(new mysqli_sql_exception("unable to update a checkout that does not exist"));
		}
		// create a query template
		$query = "UPDATE checkout SET checkoutId = ?, orderId = ? WHERE checkoutId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ii", $this->checkoutId, $this->orderId);
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
	 * gets the checkout by checkoutId
	 *
	 * @param resource $mysqli pointer to mysql connection, by reference
	 * @param int $checkoutId checkout id to search for
	 * @return mixed array of checkouts found, or null if not found
	 * @throws mysqli_sql_exception when mysql related errors occur
	 **/
	public static function getCheckoutByCheckoutId(&$mysqli, $checkoutId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "obeject" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		// sanitize the description before searching
		$checkoutId = trim($checkoutId);
		$checkoutId = filter_var($checkoutId, FILTER_VALIDATE_INT);
		// create query template
		$query = "SELECT checkoutId, orderId FROM checkout WHERE checkoutId LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		// bind the checkout id to the place holder in the template
		$checkoutId = "%$checkoutId%";
		$wasClean = $statement->bind_param("i", $checkoutId);
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
		// build an array of checkouts
		$checkouts = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$checkout = new checkout($row["checkoutId"], $row["orderId"]);
				$checkouts[] = $checkout;
			} catch(Exception $exception) {
				// if the row couldnt be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}
		// count the results in the array and return:
		// 1) null if 0 results
		// 2) a single object if 1 result
		// 3) the entire array if > 1 result
		$numberOfCheckouts = count($checkouts);
		if($numberOfCheckouts === 0) {
			return (null);
		} else if($numberOfCheckouts === 1) {
			return ($checkouts[0]);
		} else {
			return ($checkouts);
		}
	}
}