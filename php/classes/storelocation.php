<?php
/**
 * This class represents a store's pick-up locations. This is a weak entity (the strong entities are store
 * and location).
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
	 * @var boolean $inserted becomes true if inserted into the database
	 */
	private $inserted = false;

	/**
	 * constructor of this storeLocation
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

		$this->inserted = true;
		$statement->close();
	}

	/**
	 * check if the order product has been inserted
	 *
	 * @return true if the order product has been inserted
	 */
	public function isInserted() {
		return $this->inserted;
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

		// enforce that the store id and location id is not null (i.e., don't delete a storeLocation that hasn't been inserted)
		if($this->storeId === null || $this->locationId === null) {
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
	/**
	 * gets all Store Locations
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @return mixed array of Store Locations found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getAllStoreLocations(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// create query template
		$query	 = "SELECT storeId, locationId FROM storeLocation";
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

		// build an array of storeLocation
		$storeLocations = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$storeLocation	= new StoreLocation($row["storeId"], $row["locationId"]);
				$storeLocations[] = $storeLocation;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if >= 1 result
		$numberOfStoreLocations = count($storeLocations);
		if($numberOfStoreLocations === 0) {
			return(null);
		} else {
			return($storeLocations);
		}
	}
	/**
	 * gets all Store Locations by Store id
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @return mixed array of Store Locations found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getAllStoreLocationsByStoreId(&$mysqli, $storeId) {
		// handle degenerate cases
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
		// create query template
		$query	 = "SELECT storeId, locationId FROM storeLocation WHERE storeId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the store id to the place holder in the template
		$wasClean = $statement->bind_param("i", $storeId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
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

		// build an array of storeLocation
		$storeLocations = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$storeLocation	= new StoreLocation($row["storeId"], $row["locationId"]);
				$storeLocations[] = $storeLocation;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}
		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if >= 1 result
		$numberOfStoreLocations = count($storeLocations);
		if($numberOfStoreLocations === 0) {
			return(null);
		} else {
			return($storeLocations);
		}
	}

}
?>