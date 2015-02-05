<?php
/**
 * This is the class for the storeLocation weak entity of farmtoyou
 *
 * @author <alonso@hermesdevelopment.com>
 */
class StoreLocation {

	/**
	 * @var int $storeId the id of the store. Foreign Key to the store entity
	 */
	private $storeId;

	/**
	 * @var int $locationId the id of the location. Foreign Key to the location entity
	 */
	private $locationId;
	/**
	 * constructor of this orderProduct
	 *
	 * @param int $newStoreId id of the store
	 * @param int $newLocationId id of the location
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds
	 */
	public function __construct($newStoreId, $newLocationId) {
		try {
			$this->setStoreId($newStoreId);
			$this->setLocationId($newLocationId);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor for the store id
	 *
	 * @return int value for the store id
	 */
	public function getStoreId() {
		return $this->storeId;
	}

	/**
	 * mutator for the store id
	 *
	 * @param int $newStoreId for the store id
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if $newStoreId is less than 0
	 */
	public function setStoreId($newStoreId) {
		$newStoreId = filter_var($newStoreId, FILTER_VALIDATE_INT);
		if($newStoreId === false) {
			throw(new InvalidArgumentException("store id is not a valid integer"));
		}

		if($newStoreId <= 0) {
			throw(new RangeException("store id must be positive"));
		}

		$this->storeId = intval($newStoreId);
	}

	/**
	 * accessor for the location id
	 *
	 * @return int value for the location id
	 */
	public function getLocationId() {
		return $this->locationId;
	}

	/**
	 * mutator for the location id
	 *
	 * @param int $newLocationId for the location id
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if $newLocationId is less than 0
	 */
	public function setLocationId($newLocationId) {
		$newLocationId = filter_var($newLocationId, FILTER_VALIDATE_INT);
		if($newLocationId === false) {
			throw(new InvalidArgumentException("location id is not a valid integer"));
		}

		if($newLocationId <= 0) {
			throw(new RangeException("location id must be positive"));
		}

		$this->locationId = intval($newLocationId);
	}

	/**
	 * insert this storeLocation into mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public function insert(&$mysqli) {
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		$query	 = "INSERT INTO storeLocation(storeId, locationId) VALUES(?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		$wasClean	  = $statement->bind_param("ii", $this->storeId, $this->locationId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		$statement->close();
	}
	/**
	 * deletes this StoreLocation from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

// enforce that the store id and location id is not null (i.e., don't delete a tweet that hasn't been inserted)
		if($this->storeId && $this->locationId === null) {
			throw(new mysqli_sql_exception("unable to delete a store location that does not exist"));
		}

// create query template
		$query	 = "DELETE FROM storeLocation WHERE storeId = ? AND locationId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ii", $this->storeId, $this->locationId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

// clean up the statement
		$statement->close();
	}
	/**
	 * get the store location by the store id and location id
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public function getStoreLocationByStoreIdAndLocationId(&$mysqli, $storeId, $locationId) {
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		$storeId = filter_var($storeId, FILTER_VALIDATE_INT);
		if($storeId === false) {
			throw(new mysqli_sql_exception("store id is not an integer"));
		}
		if($storeId <= 0) {
			throw(new mysqli_sql_exception("store id is not positive"));
		}

		$locationId = filter_var($locationId, FILTER_VALIDATE_INT);
		if($locationId === false) {
			throw(new mysqli_sql_exception("location id is not an integer"));
		}
		if($locationId <= 0) {
			throw(new mysqli_sql_exception("location id is not positive"));
		}

		$query	 = "SELECT storeId, locationId FROM storeLocation WHERE storeId = ? AND locationId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("ii", $storeId, $locationId);
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

		try {
			$storeLocation = null;
			$row   = $result->fetch_assoc();
			if($row !== null) {
				$storeLocation	= new StoreLocation($row["storeId"], $row["locationId"]);
			}
		} catch(Exception $exception) {
			throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
		}

		$result->free();
		$statement->close();
		return($storeLocation);
	}
}
?>