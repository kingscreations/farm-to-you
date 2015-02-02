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
}
?>