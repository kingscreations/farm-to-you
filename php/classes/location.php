<?php
/**
 * This class represents a pick-up location that a merchant user would create for their stores in order get
 * their products to clients.
 *
 * @author Alonso Indacochea <alonso@hermesdevelopment.com>
 **/

class Location {
	/**
	 * id for the location, this is the primary key
	 */
	private $locationId;

	/**
	 * name of the location
	 **/
	private $locationName;

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
	 * search by address or name
	 */
	private $search;

	/**
	 * constructor for this location class
	 *
	 * @param mixed $newLocationId id of the location
	 * @param string $newLocationName name of the location
	 * @param mixed $newCountry country of the location or null if no input
	 * @param string $newState state of the location
	 * @param string $newCity city of the location
	 * @param string $newZipCode zip code of the location
	 * @param string $newAddress1 first line of the address of the location
	 * @param mixed $newAddress2 second line of the address of the location or null if no input
	 * @throws InvalidArgumentException it data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g. strings too long, negative integers)
	 **/
	public function __construct($newLocationId, $newLocationName, $newCountry, $newState, $newCity, $newZipCode,
										 $newAddress1, $newAddress2 = null) {
		try {
			$this->setLocationId($newLocationId);
			$this->setLocationName($newLocationName);
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
	 * check equality between two locations
	 *
	 * @param mixed $locationToCompare the location to compare with the current location object
	 */
	public function equals(Location $location) {
		if($this->address1 === $location->getAddress1() && $this->zipCode === $this->getZipCode()) {
			return true;
		}
		return false;
	}

	/**
	 * accessor method for the locationId
	 *
	 * @return mixed value of locationId
	 **/
	public function getLocationId() {
		return ($this->locationId);
	}

	/**
	 * mutator method for locationId
	 *
	 * @param mixed $newLocationId new value of $locationId
	 * @throws InvalidArgumentException if the $newLocationId is not an integer
	 * @throws RangeException if the $newLocationId is not positive
	 **/
	public function setLocationId($newLocationId) {
		// base case: if the location id is null, this a new location without a mySQL assigned id (yet)
		if($newLocationId === null) {
			$this->locationId = null;
			return;
		}
		// verify the location id is valid
		$newLocationId = filter_var($newLocationId, FILTER_VALIDATE_INT);
		if($newLocationId === false) {
			throw(new InvalidArgumentException("location id is not a valid integer"));
		}
		// verify the location id is positive
		if($newLocationId <= 0) {
			throw(new RangeException("location id is not positive"));
		}
		// convert and store the location id
		$this->locationId = intval($newLocationId);
	}
	/**
	 * accessor method for location name
	 *
	 * @return string value of location name
	 **/
	public function getLocationName() {
		return ($this->locationName);
	}

	/**
	 * mutator method for location name
	 *
	 * @param string $newLocationName new value of location name
	 * @throws InvalidArgumentException if $newLocationName is not a string or insecure
	 * @throws RangeException if $newLocationName is > 100 characters
	 **/
	public function setLocationName($newLocationName) {
		// verify that the location name is secure
		$newLocationName = trim($newLocationName);
		$newLocationName = filter_var($newLocationName, FILTER_SANITIZE_STRING);
		if(empty($newLocationName) === true) {
			throw(new InvalidArgumentException("location name is empty or insecure"));
		}

		// verify the location name will fit in the database
		if(strlen($newLocationName) > 100) {
			throw(new RangeException("location name too large"));
		}

		// store the location name
		$this->locationName = $newLocationName;
	}
	/**
	 * accessor method for location country
	 *
	 * @return string value of location country
	 **/
	public function getCountry() {
		return ($this->country);
	}

	/**
	 * mutator method for location country
	 *
	 * @param string $newCountry new value of location country
	 * @throws InvalidArgumentException if $newCountry is not a string or insecure
	 * @throws RangeException if $newCountry is > 2 characters
	 **/
	public function setCountry($newCountry) {
		if($newCountry === null) {
			$this->country = null;
			return;
		}

		// verify that the location country is secure
		$newCountry = trim($newCountry);
		$newCountry = filter_var($newCountry, FILTER_SANITIZE_STRING);
//		if(empty($newCountry) === true) {
//			throw(new InvalidArgumentException("country name is empty or insecure"));
//		}

		// verify the location country will fit in the database
		if(strlen($newCountry) > 2) {
			throw(new RangeException("country name too large"));
		}

		// store the location country
		$this->country = $newCountry;
	}
	/**
	 * accessor method for location state
	 *
	 * @return string value of location state
	 **/
	public function getState() {
		return ($this->state);
	}

	/**
	 * mutator method for location state
	 *
	 * @param string $newState new value of location state
	 * @throws InvalidArgumentException if $newState is not a string or insecure
	 * @throws RangeException if $newState is > 2 characters
	 **/
	public function setState($newState) {
		// verify that the location state is secure
		$newState = trim($newState);
		$newState = filter_var($newState, FILTER_SANITIZE_STRING);
		if(empty($newState) === true) {
			throw(new InvalidArgumentException("state name is empty or insecure"));
		}

		// verify the location state will fit in the database
		if(strlen($newState) > 2) {
			throw(new RangeException("state name too large"));
		}

		// store the location state
		$this->state = $newState;
	}

	/**
	 * accessor method for location city
	 *
	 * @return string value of location city
	 **/
	public function getCity() {
		return ($this->city);
	}

	/**
	 * mutator method for location city
	 *
	 * @param string $newCity new value of location city
	 * @throws InvalidArgumentException if $newCity is not a string or insecure
	 * @throws RangeException if $newCity is > 100 characters
	 **/
	public function setCity($newCity) {
		// verify that the location city is secure
		$newCity = trim($newCity);
		$newCity = filter_var($newCity, FILTER_SANITIZE_STRING);
		if(empty($newCity) === true) {
			throw(new InvalidArgumentException("city name is empty or insecure"));
		}
		// verify the location city will fit in the database
		if(strlen($newCity) > 100) {
			throw(new RangeException("city name too large"));
		}

		// store the location city
		$this->city = $newCity;
	}

	/**
	 * accessor method for location zip code
	 *
	 * @return string value of location zip code
	 **/
	public function getZipCode() {
		return ($this->zipCode);
	}

	/**
	 * mutator method for location zip code
	 *
	 * @param string $newZipCode new value of location zip code
	 * @throws InvalidArgumentException if $newZipCode is not a string or insecure
	 * @throws RangeException if $newZipCode is > 10 characters
	 **/
	public function setZipCode($newZipCode) {
		// verify that the location zip code is secure
		$newZipCode = trim($newZipCode);
		$newZipCode = filter_var($newZipCode, FILTER_SANITIZE_STRING);
		if(empty($newZipCode) === true) {
			throw(new InvalidArgumentException("zip code is empty or insecure"));
		}

		// verify the location zip code will fit in the database
		if(strlen($newZipCode) > 10) {
			throw(new RangeException("zip code too large"));
		}

		// store the location zip code
		$this->zipCode = $newZipCode;
	}
	/**
	 * accessor method for location address line 1
	 *
	 * @return string value of location address line 1
	 **/
	public function getAddress1() {
		return ($this->address1);
	}

	/**
	 * mutator method for location address line 1
	 *
	 * @param string $newAddress1 new value of location address line 1
	 * @throws InvalidArgumentException if $newAddress1 is not a string or insecure
	 * @throws RangeException if $newAddress1 is > 150 characters
	 **/
	public function setAddress1($newAddress1) {
		// verify that the location address line 1 is secure
		$newAddress1 = trim($newAddress1);
		$newAddress1 = filter_var($newAddress1, FILTER_SANITIZE_STRING);
		if(empty($newAddress1) === true) {
			throw(new InvalidArgumentException("address 1 is empty or insecure"));
		}

		// verify the location address line 1 will fit in the database
		if(strlen($newAddress1) > 150) {
			throw(new RangeException("address 1 too large"));
		}

		// store the location address line 1
		$this->address1 = $newAddress1;
	}
	/**
	 * accessor method for location address line 2
	 *
	 * @return string value of location address line 2
	 **/
	public function getAddress2() {
		return ($this->address2);
	}

	/**
	 * mutator method for location address line 2
	 *
	 * @param string $newAddress2 new value of location address line 2
	 * @throws InvalidArgumentException if $newAddress2 is not a string or insecure
	 * @throws RangeException if $newAddress2 is > 150 characters
	 **/
	public function setAddress2($newAddress2) {
		if($newAddress2 === null) {
			$this->address2 = null;
			return;
		}

		// verify that the location address line 2 is secure
		$newAddress2 = trim($newAddress2);
		$newAddress2 = filter_var($newAddress2, FILTER_SANITIZE_STRING);
//		if(empty($newAddress2) === true) {
//			throw(new InvalidArgumentException("address 2 is empty or insecure"));
//		}

		// verify the location address line 2 will fit in the database
		if(strlen($newAddress2) > 150) {
			throw(new RangeException("address 2 too large"));
		}

		// store the location address line 2
		$this->address2 = $newAddress2;
	}
	/**
	 * inserts this location into mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		// enforce the locationId is null (i.e., don't insert a location that already exists)
		if($this->locationId !== null) {
			throw(new mysqli_sql_exception("this location already exists"));
		}
		// create query template
		$query = "INSERT INTO location(locationName, country, state, city, zipCode, address1, address2) VALUES (?, ?, ?, ?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("sssssss", $this->locationName, $this->country, $this->state, $this->city, $this->zipCode, $this->address1, $this->address2);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters:"));
		}
		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement"));
		}
		// update the null locationId with what mysql just gave us
		$this->locationId = $mysqli->insert_id;
		// clean up the statement
		$statement->close();
	}

	/**
	 * deletes this location from mysql
	 *
	 * @param resource $mysqli pointer to mysql connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		// enforce the locationId is not null (i.e., don't delete a location that has not been inserted)
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
	 * updates the location in mySQL
	 *
	 * @param resource $mysqli pointer to mysql connection, by reference
	 * @throws mysqli_sql_exception when mysql related errors occur
	 **/
	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		// enforce the locationId is not null (i.e., don't update a location that hasn't been inserted)
		if($this->locationId === null) {
			throw(new mysqli_sql_exception("unable to update a location that does not exist"));
		}
		// create a query template
		$query = "UPDATE location SET locationName = ?, country = ?, state = ?, city = ?, zipCode = ?, address1 = ?, address2 = ? WHERE locationId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("sssssssi", $this->locationName, $this->country, $this->state, $this->city, $this->zipCode, $this->address1, $this->address2, $this->locationId);
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
	 * gets the location by locationId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $locationId location id to search for
	 * @return mixed Location found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getLocationByLocationId(&$mysqli, $locationId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the locationId before searching
		$locationId = filter_var($locationId, FILTER_VALIDATE_INT);
		if($locationId === false) {
			throw(new mysqli_sql_exception("location id is not an integer"));
		}
		if($locationId <= 0) {
			throw(new mysqli_sql_exception("location id is not positive"));
		}

		// create query template
		$query = "SELECT locationId, locationName, country, state, city, zipCode, address1, address2 FROM location WHERE locationId = ?";
//		var_dump('before');
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

//		var_dump($statement);
		// bind the location id to the place holder in the template
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

		// grab the location from mySQL
		try {
			$location = null;
			$row = $result->fetch_assoc();
			if($row !== null) {
				$location = new Location($row["locationId"], $row["locationName"],$row["country"], $row["state"], $row["city"], $row["zipCode"], $row["address1"], $row["address2"]);
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
	 * gets the location by name
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $locationName name to search for
	 * @return mixed array of Locations found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getLocationByLocationName(&$mysqli, $locationName) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the city before searching
		$locationName = trim($locationName);
		$locationName = filter_var($locationName, FILTER_SANITIZE_STRING);

		// create query template
		$query	 = "SELECT locationId, locationName, country, state, city, zipCode, address1, address2 FROM location WHERE locationName LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the city to the place holder in the template
		$locationName = "%$locationName%";
		$wasClean = $statement->bind_param("s", $locationName);
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

		// build an array of location
		$locations = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$location = new Location($row["locationId"], $row["locationName"],$row["country"], $row["state"], $row["city"], $row["zipCode"], $row["address1"], $row["address2"]);
				$locations[] = $location;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if >= 1 result
		$numberOfLocations = count($locations);
		if($numberOfLocations === 0) {
			return(null);
		} else {
			return($locations);
		}
	}
	/**
	 * gets the location by city
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $city city to search for
	 * @return mixed array of Locations found, Location found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getLocationByCity(&$mysqli, $city) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the city before searching
		$city = trim($city);
		$city = filter_var($city, FILTER_SANITIZE_STRING);

		// create query template
		$query	 = "SELECT locationId, locationName, country, state, city, zipCode, address1, address2 FROM location WHERE city LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the city to the place holder in the template
		$city = "%$city%";
		$wasClean = $statement->bind_param("s", $city);
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

		// build an array of location
		$locations = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$location = new Location($row["locationId"], $row["locationName"],$row["country"], $row["state"], $row["city"], $row["zipCode"], $row["address1"], $row["address2"]);
				$locations[] = $location;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if >= 1 result
		$numberOfLocations = count($locations);
		if($numberOfLocations === 0) {
			return(null);
		} else {
			return($locations);
		}
	}
	/**
	 * gets the Location by zip code
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $zipCode zip code to search for
	 * @return mixed array of Locations found, Location found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getLocationByZipCode(&$mysqli, $zipCode) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the zip code before searching
		$zipCode = trim($zipCode);
		$zipCode = filter_var($zipCode, FILTER_SANITIZE_STRING);

		// create query template
		$query	 = "SELECT locationId, locationName, country, state, city, zipCode, address1, address2 FROM location WHERE zipCode LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the zip code to the place holder in the template
		$wasClean = $statement->bind_param("s", $zipCode);
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

		// build an array of location
		$locations = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$location = new Location($row["locationId"], $row["locationName"],$row["country"], $row["state"], $row["city"], $row["zipCode"], $row["address1"], $row["address2"]);
				$locations[] = $location;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if >= 1 result
		$numberOfLocations = count($locations);
		if($numberOfLocations === 0) {
			return(null);
		} else {
			return($locations);
		}
	}
	/**
	 * gets the Location by address line 1
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $address1 address line 1 to search for
	 * @return mixed array of Locations found, Location found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getLocationByAddress1(&$mysqli, $address1) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize address line 1 before searching
		$address1 = trim($address1);
		$address1 = filter_var($address1, FILTER_SANITIZE_STRING);

		// create query template
		$query	 = "SELECT locationId, locationName, country, state, city, zipCode, address1, address2 FROM location WHERE address1 LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind address line 1 to the place holder in the template
		$address1 = "%$address1%";
		$wasClean = $statement->bind_param("s", $address1);
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

		// build an array of location
		$locations = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$location = new Location($row["locationId"], $row["locationName"],$row["country"], $row["state"], $row["city"], $row["zipCode"], $row["address1"], $row["address2"]);
				$locations[] = $location;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if >= 1 result
		$numberOfLocations = count($locations);
		if($numberOfLocations === 0) {
			return(null);
		} else {
			return($locations);
		}
	}
	/**
	 * gets all Locations
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @return mixed array of Locations found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getAllLocations(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// create query template
		$query	 = "SELECT locationId, locationName, country, state, city, zipCode, address1, address2 FROM location";
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

		// build an array of location
		$locations = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$location = new Location($row["locationId"], $row["locationName"],$row["country"], $row["state"], $row["city"], $row["zipCode"], $row["address1"], $row["address2"]);
				$locations[] = $location;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if >= 1 result
		$numberOfLocations = count($locations);
		if($numberOfLocations === 0) {
			return(null);
		} else {
			return($locations);
		}
	}
	public static function getLocationByNameOrAddress(&$mysqli, $search) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize address line 1 before searching
		$search = trim($search);
		$search = filter_var($search, FILTER_SANITIZE_STRING);

		// create query template
		$query	 = "SELECT locationId, locationName, country, state, city, zipCode, address1, address2 FROM location WHERE address1 LIKE ? OR locationName LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind address line 1 to the place holder in the template
		$search = "%$search%";
		$wasClean = $statement->bind_param("ss", $search, $search);
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

		// build an array of location
		$locations = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$location = new Location($row["locationId"], $row["locationName"],$row["country"], $row["state"], $row["city"], $row["zipCode"], $row["address1"], $row["address2"]);
				$locations[] = $location;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if >= 1 result
		$numberOfLocations = count($locations);
		if($numberOfLocations === 0) {
			return(null);
		} else {
			return($locations);
		}
	}
}
?>