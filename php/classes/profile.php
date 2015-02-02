<?php
/**
 * the creation of the users profile class
 * This class is a collection of user data
 *
 * @jason king <jason@kingscreations.org>
 **/
class Profile {
	/**
	 * id for this profile; this is the primary key
	 **/
	private $profileId;
	/**
	 *  users first name
	 **/
	private $firstName;
	/**
	 *  users last name
	 **/
	private $lastName;
	/**
	 * users phone number
	 **/
	private $phone;
	/**
	 * users profile type
	 **/
	private $profileType;
	/**
	 * users customer token?
	 **/
	private $customerToken;
	/**
	 * users image path?
	 **/
	private $imagePath;
	/**
	 * reference to the users Id; this is a foreign key
	 **/
	private $userId;

	/**
	 * constructor for the users profile
	 *
	 * @param int $newProfileId id of this users profile or null if a new profile
	 * @param string $newFirstName id of users profile
	 * @param string $newLastName id of users profile
	 * @param int $phone id of users phone number
	 * @param string $profileType of users profile type
	 * @param string $customerToken
	 * @param string $imagePath
	 * @param int $userId of the users profile
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., strings too lo
	 **/
	public function __construct($profileId, $firstName, $lastName, $phone, $profileType, $customerToken, $imagePath, $userId = null) {
		try {
			$this->setProfileId($newProfileId);
			$this->setFirstName($newFirstName);
			$this->setLastName($newLastName);
			$this->setPhone($newPhone);
			$this->setProfileType($newProfileType);
			$this->setCustomerToken($newCustomerToken);
			$this->setImagePath($newImagePath);
			$this->setUserId($newUserId);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor method for $profileId
	 *
	 * @return int value of $profileId
	 **/
	public function getProfileId() {
		return ($this->profileId);
	}

	/**
	 * mutator method for profileId
	 *
	 * @param int $newProfileId new value of profile id
	 * @throws InvalidArgumentException if $newUserId is not an integer or not positive
	 * @throws RangeException if $newUserId is not positive
	 **/
	public function setProfileId($newProfileId) {
		// base case: if the course id is null, this is classes new course without classes mySQL assigned id (yet)
		if($newProfileId === null) {
			$this->profileId = null;
			return;
		}
		//verify the profile id is valid
		$newProfileId = filter_var($newProfileId, FILTER_VALIDATE_INT);
		if($newProfileId === false) {
			throw(new InvalidArgumentException("profile id is not classes valid integer"));
		}
		//verify the profile id is positive
		if($newProfileId <= 0) {
			throw(new RangeException("profile id is not positive"));
		}
		//convert and store the course id
		$this->profileId = intval($newProfileId);
	}

	/**
	 * accessor method for $firstName
	 *
	 * @return string value of $firstName
	 **/
	public function getFirstName() {
		return ($this->firstName);
	}

	/**
	 * mutator method for firstName
	 *
	 * @param string $newFirstName of users first name
	 * @throws InvalidArgumentException if $newFirstName is not a string or insecure
	 * @throws RangeException if $newFirstName is > 45 characters
	 **/
	public function setFirstName($newFirstName) {
		// verify the firstName content is secure
		$newFirstName = trim($newFirstName);
		$newFirstName = filter_var($newFirstName, FILTER_SANITIZE_STRING);
		if(empty($newFirstName) === true) {
			throw(new InvalidArgumentException("first name content is empty or insecure"));
		}

		// verify the first name content will fit in the database
		if(strlen($newFirstName) > 45) {
			throw(new RangeException("first name content too large"));
		}

		// store the first name content
		$this->firstName = $newFirstName;
	}

	/**
	 * accessor method for $lastName
	 *
	 * @return string value of $lastName
	 **/
	public function getLastName() {
		return ($this->lastName);
	}

	/**
	 * mutator method for lastName
	 *
	 * @param string $newLastName value for users last name
	 * @throws InvalidArgumentException if $newLastName is not a string or insecure
	 * @throws RangeException if $newLastName is > 45 characters
	 **/
	public function setLastName($newLastName) {
		// verify the last name content is secure
		$newLastName = trim($newLastName);
		$newLastName = filter_var($newLastName, FILTER_SANITIZE_STRING);
		if(empty($newLastName) === true) {
			throw(new InvalidArgumentException("last name content is empty or insecure"));
		}

		// verify the last name content will fit in the database
		if(strlen($newLastName) > 45) {
			throw(new RangeException("last name content too large"));
		}

		// store the last name content
		$this->lastName = $newLastName;
	}

	/**
	 * accessor method for $phone
	 *
	 * @return int value of $phone
	 **/
	public function getPhone() {
		return ($this->phone);
	}

	/**
	 * mutator method for phone
	 *
	 *
	 **/

	/**
	 * accessor method for $profileType
	 *
	 * @return string value of $profileType
	 **/
	public function getProfileType() {
		return ($this->profileType);
	}

	/**
	 * mutator method for profile Type
	 *
	 *
	 **/

	/**
	 * accessor method for $customerToken
	 *
	 * @return string value of $customerToken
	 **/
	public function getCustomerToken() {
		return ($this->customerToken);
	}

	/**
	 * mutator method for customer Token
	 *
	 *
	 **/

	/**
	 * accessor method for $imagePath
	 *
	 * @return string value of $imagePath
	 **/
	public function getImagePath() {
		return ($this->imagePath);
	}

	/**
	 * mutator method for image path
	 *
	 *
	 **/

	/**
	 * accessor method for $userId
	 *
	 * @return int value of $userId. This is a Foreign Key: user(userId)
	 **/
	public function getUserId() {
		return ($this->userId);
	}

	/**
	 * mutator method for userId
	 *
	 * @param int $newUserId new value of user id
	 * @throws InvalidArgumentException if $newUserId is not an integer or not positive
	 * @throws RangeException if $newUserId is not positive
	 **/
	public function setUserId($newUserId) {
		// verify the user id is valid
		$newUserId = filter_var($newUserId, FILTER_VALIDATE_INT);
		if($newUserId === false) {
			throw(new InvalidArgumentException("user id is not a valid integer"));
		}

		// verify the user id is positive
		if($newUserId <= 0) {
			throw(new RangeException("user id is not positive"));
		}

		// convert and store the user id
		$this->userId = intval($newUserId);
	}
}
