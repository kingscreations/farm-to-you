<?php
/**
 * This is the class for the location function of farmtoyou
 *
 * @author Alonso Indacochea <alonso@hermesdevelopment.com>
 **/

class Location {
	/**
	 * id for the location, this is the primary key
	 */
	private $locationId;

	/**
	 * country of the location
	 **/
	private $country;

	/**
	 * state of the location
	 **/
	private $state;

	/**
	 * city of the location
	 **/
	private $city;

	/**
	 * zip code of the location
	 **/
	private $zipCode;

	/**
	 * first line of address of the location
	 **/
	private $address1;

	/**
	 * second line of address of the location
	 **/
	private $address2;


	/**
	 * constructor for this store class
	 *
	 * @param int $newStoreId id of the store
	 * @param int $newProfileId id of the profile associated with the store
	 * @param mixed $newCreationDate date and time store was created or null if set to current date and time
	 * @param string $newStoreName name of the store
	 * @param string $newImagePath path of image associated with the store or null if none
	 * @throws InvalidArgumentException it data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g. strings too long, negative integers)
	 **/
	public function __construct($newLocationId, $newCountry, $newState, $newCity, $newZipCode, $newAddress1, $newAddress2 = null) {
		try {
			$this->setLocationId($newLocationId);
			$this->setCountry($newCountry);
			$this->setState($newState);
			$this->setCity($newCity);
			$this->setZipCode($newZipCode);
			$this->setAddress1($newAddress1);
			$this->setAddress2($newAddress2);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor method for the storeId
	 *
	 * @return int value of storeId
	 **/
	public function getLocationId() {
		return ($this->locationId);
	}

	/**
	 * mutator method for storeId
	 *
	 * @param int $newStoreId new value of $storeId
	 * @throws InvalidArgumentException if the $storeId is not an integer
	 * @throws RangeException if the $storeId is not positive
	 **/
	public function setLocationId($newLocationId) {
		// verify the store id is valid
		$newLocationId = filter_var($newLocationId, FILTER_VALIDATE_INT);
		if($newLocationId === false) {
			throw(new InvalidArgumentException("location id is not a valid integer"));
		}
		// verify the store id is positive
		if($newLocationId <= 0) {
			throw(new RangeException("location id is not positive"));
		}
		// convert and store the user id
		$this->locationId = intval($newLocationId);
	}
	/**
	 * accessor method for store name
	 *
	 * @return string value of store name
	 **/
	public function getCountry() {
		return ($this->country);
	}

	/**
	 * mutator method for tweet content
	 *
	 * @param string $newStoreName new value of tweet content
	 * @throws InvalidArgumentException if $newStoreName is not a string or insecure
	 * @throws RangeException if $newStoreName is > 100 characters
	 **/
	public function setCountry($newCountry) {
// verify that the store name is secure
		$newCountry = trim($newCountry);
		$newCountry = filter_var($newCountry, FILTER_SANITIZE_STRING);
		if(empty($newCountry) === true) {
			throw(new InvalidArgumentException("country name is empty or insecure"));
		}

// verify the store name will fit in the database
		if(strlen($newCountry) > 30) {
			throw(new RangeException("country name too large"));
		}

// store the store name
		$this->country = $newCountry;
	}
	/**
	 * accessor method for store name
	 *
	 * @return string value of store name
	 **/
	public function getState() {
		return ($this->state);
	}

	/**
	 * mutator method for tweet content
	 *
	 * @param string $newStoreName new value of tweet content
	 * @throws InvalidArgumentException if $newStoreName is not a string or insecure
	 * @throws RangeException if $newStoreName is > 100 characters
	 **/
	public function setState($newState) {
// verify that the store name is secure
		$newState = trim($newState);
		$newState = filter_var($newState, FILTER_SANITIZE_STRING);
		if(empty($newState) === true) {
			throw(new InvalidArgumentException("state name is empty or insecure"));
		}

// verify the store name will fit in the database
		if(strlen($newState) > 2) {
			throw(new RangeException("state name too large"));
		}

// store the store name
		$this->state = $newState;
	}

	/**
	 * accessor method for store name
	 *
	 * @return string value of store name
	 **/
	public function getCity() {
		return ($this->city);
	}

	/**
	 * mutator method for tweet content
	 *
	 * @param string $newStoreName new value of tweet content
	 * @throws InvalidArgumentException if $newStoreName is not a string or insecure
	 * @throws RangeException if $newStoreName is > 100 characters
	 **/
	public function setCity($newCity) {
// verify that the store name is secure
		$newCity = trim($newCity);
		$newCity = filter_var($newCity, FILTER_SANITIZE_STRING);
		if(empty($newCity) === true) {
			throw(new InvalidArgumentException("city name is empty or insecure"));
		}

// verify the store name will fit in the database
		if(strlen($newCity) > 40) {
			throw(new RangeException("city name too large"));
		}

// store the store name
		$this->city = $newCity;
	}

	/**
	 * accessor method for store name
	 *
	 * @return string value of store name
	 **/
	public function getZipCode() {
		return ($this->zipCode);
	}

	/**
	 * mutator method for tweet content
	 *
	 * @param string $newStoreName new value of tweet content
	 * @throws InvalidArgumentException if $newStoreName is not a string or insecure
	 * @throws RangeException if $newStoreName is > 100 characters
	 **/
	public function setZipCode($newZipCode) {
// verify that the store name is secure
		$newZipCode = trim($newZipCode);
		$newZipCode = filter_var($newZipCode, FILTER_SANITIZE_STRING);
		if(empty($newZipCode) === true) {
			throw(new InvalidArgumentException("zip code is empty or insecure"));
		}

// verify the store name will fit in the database
		if(strlen($newZipCode) > 5) {
			throw(new RangeException("zip code too large"));
		}

// store the store name
		$this->zipCode = $newZipCode;
	}
	/**
	 * accessor method for store name
	 *
	 * @return string value of store name
	 **/
	public function getAddress1() {
		return ($this->address1);
	}

	/**
	 * mutator method for tweet content
	 *
	 * @param string $newStoreName new value of tweet content
	 * @throws InvalidArgumentException if $newStoreName is not a string or insecure
	 * @throws RangeException if $newStoreName is > 100 characters
	 **/
	public function setAddress1($newAddress1) {
// verify that the store name is secure
		$newAddress1 = trim($newAddress1);
		$newAddress1 = filter_var($newAddress1, FILTER_SANITIZE_STRING);
		if(empty($newAddress1) === true) {
			throw(new InvalidArgumentException("address 1 is empty or insecure"));
		}

// verify the store name will fit in the database
		if(strlen($newAddress1) > 5) {
			throw(new RangeException("address 1 too large"));
		}

// store the store name
		$this->address1 = $newAddress1;
	}
	/**
	 * accessor method for store name
	 *
	 * @return string value of store name
	 **/
	public function getAddress2() {
		return ($this->address2);
	}

	/**
	 * mutator method for tweet content
	 *
	 * @param string $newStoreName new value of tweet content
	 * @throws InvalidArgumentException if $newStoreName is not a string or insecure
	 * @throws RangeException if $newStoreName is > 100 characters
	 **/
	public function setAddress2($newAddress2) {
// verify that the store name is secure
		$newAddress2 = trim($newAddress2);
		$newAddress2 = filter_var($newAddress2, FILTER_SANITIZE_STRING);
		if(empty($newAddress2) === true) {
			throw(new InvalidArgumentException("address 2 is empty or insecure"));
		}

// verify the store name will fit in the database
		if(strlen($newAddress2) > 5) {
			throw(new RangeException("address 2 too large"));
		}

// store the store name
		$this->address2 = $newAddress2;
	}
	/**
	 * inserts this store into mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		// enforce the storeId is null (i.e., don't insert a category that already exists)
		if($this->locationId !== null) {
			throw(new mysqli_sql_exception("this location already exists"));
		}
		// create query template
		$query = "INSERT INTO location(country, state, city, zipCode, address1, address2) VALUES (?, ?, ?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ssssss", $this->country, $this->state, $this->city, $this->zipCode, $this->address1, $this->address2);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters:"));
		}
		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement"));
		}
		// update the null storeId with what mysql just gave us
		$this->locationId = $mysqli->insert_id;
		// clean up the statement
		$statement->close();
	}

	/**
	 * deletes this store from mysql
	 *
	 * @param resource $mysqli pointer to mysql connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		// enforce the storeId is not null (i.e., don't delete a store that has not been inserted)
		if($this->locationId === null) {
			throw(new mysqli_sql_exception("unable to delete a location that does not exist"));
		}
		// create query template
		$query = "DELETE FROM location WHERE locationId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		// bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("i", $this->locationId);
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
		// enforce the storeId is not null (i.e., don't update a store that hasn't been inserted)
		if($this->locationId === null) {
			throw(new mysqli_sql_exception("unable to update a location that does not exist"));
		}
		// create a query template
		$query = "UPDATE location SET country = ?, state = ?, city = ?, zipCode = ?, address1 = ?, address2 = ? WHERE locationId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ssssssi", $this->country, $this->state, $this->city, $this->zipCode, $this->address1, $this->address2, $this->locationId);
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
	 * gets the store by storeId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $storeId store id to search for
	 * @return mixed Store found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getLocationByLocationId(&$mysqli, $locationId) {
// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

// sanitize the storeId before searching
		$locationId = filter_var($locationId, FILTER_VALIDATE_INT);
		if($locationId === false) {
			throw(new mysqli_sql_exception("location id is not an integer"));
		}
		if($locationId <= 0) {
			throw(new mysqli_sql_exception("location id is not positive"));
		}

// create query template
		$query = "SELECT locationId, country, state, city, zipCode, address1, address2 FROM location WHERE locationId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

// bind the store id to the place holder in the template
		$wasClean = $statement->bind_param("i", $locationId);
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

// grab the store from mySQL
		try {
			$location = null;
			$row = $result->fetch_assoc();
			if($row !== null) {
				$location = new Store($row["locationId"], $row["country"], $row["state"], $row["city"], $row["zipCode"], $row["address1"], $row["address2"]);
			}
		} catch(Exception $exception) {
// if the row couldn't be converted, rethrow it
			throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
		}

// free up memory and return the result
		$result->free();
		$statement->close();
		return ($location);
	}
	/**
	 * gets the store by storeName
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $storeName store name to search for
	 * @return mixed Store found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getStoreByStoreName(&$mysqli, $storeName) {
// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

// sanitize the storeName before searching
		$storeName = trim($storeName);
		$storeName = filter_var($storeName, FILTER_SANITIZE_STRING);
		if(empty($storeName) === true) {
			throw(new mysqli_sql_exception("store name is empty or insecure"));
		}
		if(strlen($storeName) > 100) {
			throw(new mysqli_sql_exception("store name too large"));
		}

// create query template
		$query = "SELECT storeId, profileId, creationDate, storeName, imagePath FROM store WHERE storeName = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

// bind the store id to the place holder in the template
		$wasClean = $statement->bind_param("s", $storeName);
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

// grab the store from mySQL
		try {
			$store = null;
			$row = $result->fetch_assoc();
			if($row !== null) {
				$store = new Store($row["storeId"], $row["profileId"], $row["storeName"], $row["imagePath"], $row["creationDate"]);
			}
		} catch(Exception $exception) {
// if the row couldn't be converted, rethrow it
			throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
		}

// free up memory and return the result
		$result->free();
		$statement->close();
		return ($store);
	}

}
?>