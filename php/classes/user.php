<?php
/**
 * the creation of the user class for farmtoyou capstone
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
	 * @throws InvalidArgumentException if $newEmail is invalid
	 * @throws RangeException if $newEmail is > 100 characters
	 */
	public function setEmail($newEmail) {
		// verify the email content is secure
		$newEmail = trim($newEmail);
		$newEmail = filter_var($newEmail, FILTER_SANITIZE_EMAIL);
		if(empty($newEmail) === true) {
			throw(new InvalidArgumentException("email address is invalid"));
		}

		// verify the email address is valid
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
	 * @param string $hash new value
	 * @throws InvalidArgumentException if $hash is not a hexadecimal digit
	 * @throws RangeException if $hash is !== 128 characters
	 *
	 **/
	public function setHash($newHash) {
		// verify the hash is a hexadecimal digit
		$newHash = trim($newHash);
		if(ctype_xdigit($newHash) === false) {
			throw(new InvalidArgumentException("hash content in not a hexadecimal digit"));
		}
		// verify the hash content will fit in the database
		if(strlen($newHash) !== 128) {
			throw(new RangeException("hash is not the correct size"));
		}

		$this->hash = $newHash;
	}

	/**
	 * accessor method for salt
	 *
	 * @return salt value
	 **/
	public function getSalt() {
		return($this->salt);
	}

	/**
	 * mutator method for salt
	 *
	 * @param string $salt new value
	 * @throws InvalidArgumentException if $salt is not a hexadecimal digit
	 * @throws RangeException if $salt is !== 32 characters
	 */
	public function setSalt($newSalt) {
		// verify the salt is a hexadecimal digit
		$newSalt = trim($newSalt);
		if(ctype_xdigit($newSalt) === false) {
			throw(new InvalidArgumentException("salt content in not a hexadecimal digit"));
		}
		// verify the salt content will fit in the database
		if(strlen($newSalt) !== 32) {
			throw(new RangeException("salt is not the correct size"));
		}

		$this->salt = $newSalt;
	}
	/**
	 * accessor method for activation
	 *
	 * @return string value for activation
	 */
	public function getActivation() {
		return($this->activation);
	}

	/**
	 * mutator method for activation
	 *
	 * @param string $activation
	 * @throws InvalidArgumentException if $activation is not a hexadecimal digit
	 * @throws RangeException if $activation is !=== 16 characters
	 */  
	public function setActivation($newActivation) {
		// verify the activation is a hexadecimal digit
		$newActivation = trim($newActivation);
		if(ctype_xdigit($newActivation) === false) {
			throw(new InvalidArgumentException("activation content in not a hexadecimal digit"));
		}
		// verify the Activation content will fit in the database
		if(strlen($newActivation) !== 16) {
			throw(new RangeException("activation is not the correct size"));
		}

		$this->activation = $newActivation;
	}


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
		$wasClean = $statement->bind_param("ssssi", $this->email, $this->hash, $this->salt, $this->activation,$this->userId);
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
	 * gets the User by userId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $userId profile content to search for
	 * @return mixed User found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getUserByUserId(&$mysqli, $userId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		// sanitize the UserId before searching
		$userId = filter_var($userId, FILTER_VALIDATE_INT);
		if($userId === false) {
			throw(new mysqli_sql_exception("user id is not an integer"));
		}
		if($userId <= 0) {
			throw(new mysqli_sql_exception("user id is not positive"));
		}
		// create query template
		$query = "SELECT userId, email, hash, salt, activation FROM user WHERE userId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		// bind the profile content to the place holder in the template
		$wasClean = $statement->bind_param("i", $userId);
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
		// grab the user from mySQL
		try {
			$user = null;
			$row = $result->fetch_assoc();
			if($row !== null) {
				$user = new User($row["userId"], $row["email"], $row["hash"], $row["salt"], $row["activation"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
		}
		// free up memory and return the result
		$result->free();
		$statement->close();
		return($user);
	}

	/**
	 * gets the User by email address
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $email profile content to search for
	 * @return mixed array of Emails found, Email found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getUserByEmail(&$mysqli, $email) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		// sanitize the description before searching
		$email = trim($email);
		$email = filter_var($email, FILTER_SANITIZE_STRING);
		// create query template
		$query	 = "SELECT UserId, lastName, email, hash, salt, activation FROM user WHERE email LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		// bind the user content to the place holder in the template
		$email = "%$email%";
		$wasClean = $statement->bind_param("s", $email);
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
		// build an array of User
		$user = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$user	= new User($row["userId"], $row["email"], $row["hash"], $row["salt"], $row["activation"]);
				$users[] = $user;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}
		// count the results in the array and return:
		// 1) null if 0 results
		// 2) a single object if 1 result
		// 3) the entire array if > 1 result
		$numberOfUsers = count($users);
		if($numberOfUsers === 0) {
			return(null);
		} else if($numberOfUsers === 1) {
			return($users[0]);
		} else {
			return($users);
		}
	}
//get user by activation
	/**
	 * gets the User by activation
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $activation content to search for
	 * @return mixed array of activations found, Activation found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getUserByActivation(&$mysqli, $activation) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		// sanitize the description before searching
		$activation = trim($activation);
		$activation = filter_var($activation, FILTER_SANITIZE_STRING);
		// create query template
		$query	 = "SELECT userId, email, hash, salt, FROM user WHERE activation LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}
		// bind the user content to the place holder in the template
		$activation = "%$activation%";
		$wasClean = $statement->bind_param("s", $activation);
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
		// build an array of user
		$user = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$user	= new User($row["userId"], $row["email"], $row["hash"], $row["salt"], $row["activation"]);
				$users[] = $user;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}
		// count the results in the array and return:
		// 1) null if 0 results
		// 2) a single object if 1 result
		// 3) the entire array if > 1 result
		$numberOfUsers = count($users);
		if($numberOfUsers === 0) {
			return(null);
		} else if($numberOfUsers === 1) {
			return($users[0]);
		} else {
			return($users);
		}
	}
}
?>