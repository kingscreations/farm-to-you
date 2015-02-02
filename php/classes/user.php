<?php
/**
/**
 * the creation of the user class
 * This class is a collection of user login data
 * @jason king <jason@kingscreations.org>
 **/
class User {
	/**
	 * id for this user; this is the primary key
	 **/
	private $userId;
	/**
	 * email of user
	 **/
	private $email;
	/**
	 * hash of user password
	 **/
	private $hash;
	/**
	 * salted hash value
	 **/
	private $salt;
	/**
	 * activation to lock account when not yet activated, or user forgot password
	 **/
	private $activation;

	/**
	 * constructor for this User
	 *
	 * @param mixed $newUserId id of this User or null if a new User
	 * @param string $newEmail of the User
	 * @param string $newHash int of users password
	 * @param string $newSalt int of password hash
	 * @param string $newActivation ??
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 **/
	public function __construct($newUserId, $newEmail, $newHash, $newSalt, $newActivation = null) {
		try {
			$this->setUserId($newUserId);
			$this->setEmail($newEmail);
			$this->setHash($newHash);
			$this->setSalt($newSalt);
			$this->setActivation($newActivation);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor method for user id
	 *
	 * @return mixed value of user id
	 **/
	public function getUserId() {
		return($this->userId);
	}

	/**
	 * mutator method for user id
	 *
	 * @param mixed $newUserId new value of user id
	 * @throws InvalidArgumentException if $newUserId is not an integer
	 * @throws RangeException if $newUserId is not positive
	 **/
	public function setUserId($newUserId) {
		// base case: if the user id is null, this a new user without a mySQL assigned id (yet)
		if($newUserId === null) {
			$this->userId = null;
			return;
		}

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

	/**
	 * accessor method for email
	 *
	 * @return string value of email
	 */
	public function getEmail() {
		return($this->email);
	}

	/**
	 * mutator method for email
	 *
	 * @param string $email new value of user email
	 * @throws InvalidArgumentException if $newEmail is not a string or insecure
	 * @throws RangeException if $newEmail is > 100 characters
	 */
	public function setEmail($newEmail) {
		// verify the email content is secure
		$newEmail = trim($newEmail);
		$newEmail = filter_var($newEmail, FILTER_SANITIZE_STRING);
		if(empty($newEmail) === true) {
			throw(new InvalidArgumentException("email content is empty or insecure"));
		}

		// verify the email content will fit in the database
		if(strlen($newEmail) > 100) {
			throw(new RangeException("email content too large"));
		}

		// store the email content
		$this->email = $newEmail;
	}

	/**
	 * accessor method for password hash
	 *
	 * @return string value of password hash
	 **/
	public function getHash() {
		return($this->hash);
	}

	/**
	 * mutator method for hash content
	 *
	 *
	 **/
		// generate a hash value

		// FACT: a hash is only hexadecimal digits
		// HINT: ctype_xdigit()

		// FACT: a hash is 128 characters long
		// HINT: strlen()

	//

	/**
	 * accessor method for salted hash
	 *
	 * @return salt value of hashed password
	 **/
	public function getSalt() {
		return($this->salt);
	}

	/**
	 * mutator method for salted hash
	 *
	 *
	 **/


	/**
	 * inserts this user into mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the userId is null
		if($this->userId !== null) {
			throw(new mysqli_sql_exception("not a new user"));
		}

		// create query template
		$query	 = "INSERT INTO user(email, hash, salt, activation) VALUES(?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ssss", $this->email, $this->hash, $this->salt, $this->activation);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// update the null userId with what mySQL just gave us
		$this->userId = $mysqli->insert_id;

		// clean up the statement
		$statement->close();
	}


	/**
	 * deletes this User from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the UserId is not null (i.e., don't delete a user that hasn't been inserted)
		if($this->userId === null) {
			throw(new mysqli_sql_exception("unable to delete a user that does not exist"));
		}

		// create query template
		$query	 = "DELETE FROM user WHERE userId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("i", $this->userId);
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
	 * updates this User in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the userId is not null (i.e., don't update a user that hasn't been inserted)
		if($this->userId === null) {
			throw(new mysqli_sql_exception("unable to update a user that does not exist"));
		}

		// create query template
		$query	 = "UPDATE user SET email = ?, hash = ?, salt = ?, activation = ? WHERE userId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ssssi",  $this->email, $this->hash, $this->salt, $this->activation,$this->userId);
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

}
?>