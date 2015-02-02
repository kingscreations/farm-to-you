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
 * users first name
 **/
private $firstName;
/**
 * users last name
 **/
private $lastName;
/**
 * users phone number
 **/
private $phone;
/**
 * users profile type, buyer or seller
 **/
private $profileType;
/**
 * users customer token from Stripe
 **/
private $customerToken;
/**
 * seller's image path
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
 * @param string $phone id of users phone number
 * @param string $profileType of users profile type
 * @param string $customerToken
 * @param string $imagePath
 * @param int $userId of the users profile
 * @throws InvalidArgumentException if data types are not valid
 * @throws RangeException if data values are out of bounds (e.g., strings too lo
 **/
public function __construct($newProfileId, $newFirstName, $newLastName, $newPhone, $newProfileType, $newCustomerToken, $newImagePath, $newUserId = null) {
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
		 * @throws InvalidArgumentException if $newPhone is not a string or insecure
		 * @throws RangeException if $newPhone is > 45 characters
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
		 * @return string value of $phone
		 **/
		public function getPhone() {
			return ($this->phone);
		}

		/**
		 * mutator method for phone
		 *
		 * @param string $newPhone of users phone #
		 * @throws InvalidArgumentException if $phone is not a string or insecure
		 * @throws RangeException if $newPhone is > 20 characters
		 **/
		public function setPhone($newPhone) {
			// verify the phone content is secure
			$newPhone = trim($newPhone);
			$newPhone = filter_var($newPhone, FILTER_SANITIZE_STRING);
			if(empty($newPhone) === true) {
				throw(new InvalidArgumentException("phone content is empty or insecure"));
			}

			// verify the phone content will fit in the database
			if(strlen($newPhone) > 20) {
				throw(new RangeException("phone content too large"));
			}

			// store the phone content
			$this->phone = $newPhone;
		}

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
		 **
	 * @param string $newProfileType of users profile
	 * @throws InvalidArgumentException if $profileType is not a string or insecure
	 * @throws RangeException if $profileType is > 10 characters
	 **/
		public function setProfileType($newProfileType) {
			// verify the profileType content is secure
			$newProfileType = trim($newProfileType);
			$newProfileType = filter_var($newProfileType, FILTER_SANITIZE_STRING);
			if(empty($newProfileType) === true) {
				throw(new InvalidArgumentException("profile type content is empty or insecure"));
			}

			// verify the profileType content will fit in the database
			if(strlen($newProfileType) > 10) {
				throw(new RangeException("profile type content too large"));
			}

			// store the first name content
			$this->profileType = $newProfileType;
		}

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
	 * @param string $customerToken
	 * @throws InvalidArgumentException if $customerToken is not a string or insecure
	 * @throws RangeException if $customerToken is > 50 characters
	 **/
		public function setCustomerToken($newCustomerToken) {
			// verify the customerToken content is secure
			$newCustomerToken = trim($newCustomerToken);
			$newCustomerToken = filter_var($newCustomerToken, FILTER_SANITIZE_STRING);
			if(empty($newCustomerToken) === true) {
				throw(new InvalidArgumentException("customer token content is empty or insecure"));
			}

			// verify the customer token content will fit in the database
			if(strlen($newCustomerToken) > 50) {
				throw(new RangeException("customer token content too large"));
			}

			// store the first name content
			$this->customerToken = $newCustomerToken;
		}

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
	 * @param string $imagePath
	 * @throws InvalidArgumentException if $imagePath is not a string or insecure
	 * @throws RangeException if $newImagePath is > 100 characters
	 **/
		public function setImagePath($newImagePath) {
			// verify the image path content is secure
			$newImagePath = trim($newImagePath);
			$newImagePath = filter_var($newImagePath, FILTER_SANITIZE_STRING);
			if(empty($newImagePath) === true) {
				throw(new InvalidArgumentException("image path content is empty or insecure"));
			}

			// verify the image path content will fit in the database
			if(strlen($newImagePath) > 100) {
				throw(new RangeException("image path content too large"));
			}

			// store the image path content
			$this->imagePath = $newImagePath;
		}

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
