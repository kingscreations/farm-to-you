<?php
/**
 * This is the class for the checkout function of farmtoyou
 *
 * This will be used for the checkouts that are handled and will be working with the order table
 * to create checkout Id's.
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
	 * date for the checkout
	 **/
	private $checkoutDate;

	/**
	 * constructor for this checkout class
	 *
	 * @param int $newCheckoutId id of the checkout
	 * @param int $newOrderId of the order
	 * @param DateTime $newCheckoutDate for the checkout
	 * @throws InvalidArgumentException it data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g. strings too long, negative integers)
	 **/
	public function __construct($newCheckoutId, $newOrderId, $newCheckoutDate = null) {
		try {
			$this->setCheckoutId($newCheckoutId);
			$this->setOrderId($newOrderId);
			$this->setCheckoutDate($newCheckoutDate);
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
		if($newCheckoutId === null) {
			$this->checkoutId = null;
			return;
		}

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
		if($newOrderId === null) {
			$this->orderId = null;
			return;
		}

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
	 * accessor method for checkout date
	 *
	 * @return DateTime value of checkout date
	 **/
	public function getCheckoutDate() {
		return ($this->checkoutDate);
	}

	/**
	 * mutator method for checkout date
	 *
	 * @param mixed $newCheckoutDate checkout date as a DateTime object or string (or null to load current time)
	 * @throws InvalidArgumentException if $newCheckoutDate is not a valid object or string
	 * @throws RangeException if $newCheckoutDate is a date that does not exist
	 **/
	public function setCheckoutDate($newCheckoutDate) {
		// base case: if the date is null, use current date and time
		if($newCheckoutDate === null) {
			$this->checkoutDate = new DateTime();
			return;
		}

		// base case: if the date is a DateTime object, there's no work to be done
		if(is_object($newCheckoutDate) === true && get_class($newCheckoutDate) === "DateTime") {
			$this->checkoutDate = $newCheckoutDate;
			return;
		}

		// treat the date as a mySQL date string: Y-m-d H:i:s
		$newCheckoutDate = trim($newCheckoutDate);
		if((preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $newCheckoutDate, $matches)) !== 1) {
			throw(new InvalidArgumentException("checkout date is not a valid date"));
		}

		// verify the date is a valid calendar date
		$year = intval($matches[1]);
		$month = intval($matches[2]);
		$day = intval($matches[3]);
		$hour = intval($matches[4]);
		$minute = intval($matches[5]);
		$second = intval($matches[6]);
		if(checkdate($month, $day, $year) === false) {
			throw(new RangeException("checkout date $newCheckoutDate is not a Gregorian date"));
		}

		// verify the time is really a valid wall clock time
		if($hour < 0 || $hour >= 24 || $minute < 0 || $minute >= 60 || $second < 0 || $second >= 60) {
			throw(new RangeException("checkout date $newCheckoutDate is not a valid time"));
		}

		// store the checkout date
		$newCheckoutDate = DateTime::createFromFormat("Y-m-d H:i:s", $newCheckoutDate);
		$this->checkoutDate = $newCheckoutDate;
	}

	/**
	 * inserts this checkout into mySQL
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
			throw(new mysqli_sql_exception("this checkout already exists"));
		}

		// create query template
		$query = "INSERT INTO checkout(orderId, checkoutDate) VALUES (?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$formattedDate = $this->checkoutDate->format("Y-m-d H:i:s");
		$wasClean = $statement->bind_param("is", $this->orderId, $formattedDate);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters:"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement" . $statement->error));
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
		$query = "UPDATE checkout SET checkoutId = ?, orderId = ?, checkoutDate = ? WHERE checkoutId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$formattedDate = $this->checkoutDate->format("Y-m-d H:i:s");
		$wasClean = $statement->bind_param("iisi", $this->checkoutId, $this->orderId, $formattedDate, $this->checkoutId);
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
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the description before searching
		$checkoutId = trim($checkoutId);
		$checkoutId = filter_var($checkoutId, FILTER_VALIDATE_INT);
		// create query template
		$query = "SELECT checkoutId, orderId, checkoutDate FROM checkout WHERE checkoutId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the checkout id to the place holder in the template
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

		// grab the checkout from mySQL
		try {
			$checkout = null;
			$row = $result->fetch_assoc();
			if($row !== null) {
				$checkout = new Checkout($row["checkoutId"], $row["orderId"], $row["checkoutDate"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
		}

		// free up memory and return the result
		$result->free();
		$statement->close();
		return ($checkout);
	}

	/**
	 * gets the checkout by date
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $checkoutDate checkout date to search for
	 * @return mixed array of checkouts found,  or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getCheckoutByCheckoutDate(&$mysqli, $checkoutDate) {
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		$checkoutDate = trim($checkoutDate);
		$checkoutDate = filter_var($checkoutDate, FILTER_SANITIZE_STRING);

		$query = "SELECT checkoutId, orderId, checkoutDate FROM checkout WHERE checkoutDate = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the checkout date to the place holder in the template
		$wasClean = $statement->bind_param("s", $checkoutDate);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

		// build an array of checkout
		$checkouts = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$checkout = new Checkout($row["checkoutId"], $row["orderId"], $row["checkoutDate"]);
				$checkouts[] = $checkout;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		$numberOfCheckouts = count($checkouts);
		if($numberOfCheckouts === 0) {
			return (null);
		} else if($numberOfCheckouts === 1) {
			return ($checkouts[0]);
		} else {
			return ($checkouts);
		}
	}

	/**
	 * gets all Checkouts
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @return mixed array of checkouts found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getAllCheckouts(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// create query template
		$query = "SELECT checkoutId, orderId, checkoutDate FROM checkout";
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
		$checkouts = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$checkout = new Checkout($row["checkoutId"], $row["orderId"], $row["checkoutDate"]);
				$checkouts[] = $checkout;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		$numberOfCheckouts = count($checkouts);
		if($numberOfCheckouts === 0) {
			return (null);
		} else {
			return ($checkouts);
		}
	}
}