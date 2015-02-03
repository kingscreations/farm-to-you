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


}
?>