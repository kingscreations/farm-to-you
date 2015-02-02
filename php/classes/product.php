<?php
/**
 * Model to connect with the product entity
 *
 * @author <fgoussin@cnm.edu>
 */
class Product {

	/**
	 * @var int $profileId id for the product. This is the primary key of the product entity.
	 */
	private $productId;

	/**
	 * @var int $profileId id for the profile. This is a foreign key to the profile entity.
	 */
	private $profileId;

	/**
	 * @var string $productName name of the product
	 */
	private $productName;

	/**
	 * @var float $productPrice price of the product
	 */
	private $productPrice;

	/**
	 * @var string $productType type of the product
	 */
	private $productType;

	/**
	 * @var float $productWeight weight of the product
	 */
	private $productWeight;


	/**
	 * constructor of this product
	 *
	 * @param int productId
	 * @param int $profileId
	 * @param string $productName
	 * @param string $productPrice
	 * @param string $productType
	 * @param float $productWeight
	 *
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds
	 */
	public function __construct($newProductId, $newProfileId, $newProductName, $newProductPrice, $newProductType, $newProductWeight) {
		try {
			$this->setProductId($newProductId);
			$this->setProfileId($newProfileId);
			$this->setProductName($newProductName);
			$this->setProductPrice($newProductPrice);
			$this->setProductType($newProductType);
			$this->setProductWeight($newProductWeight);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor for the product id
	 *
	 * @return int value for the product id
	 */
	public function getProductId() {
		return $this->productId;
	}

	/**
	 * mutator for the product id
	 *
	 * @param int value for the product id
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if $newProductId is less than 0
	 */
	public function setProductId($newProductId) {
		if($newProductId === null) {
			$this->productId = null;
			return;
		}

		$newProductId = filter_var($newProductId, FILTER_VALIDATE_INT);
		if($newProductId === false) {
			throw(new InvalidArgumentException("product id is not a valid integer"));
		}

		if($newProductId <= 0) {
			throw(new RangeException("product id must be positive"));
		}

		$this->productId = inval($newProductId);
	}

	/**
	 * accessor for the profile id
	 *
	 * @return int value for the profile id
	 */
	public function getProfileId() {
		return $this->profileId;
	}

	/**
	 * mutator for the profile id
	 *
	 * @param int value for the product id
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if $newProfileId is less than 0
	 */
	public function setProfileId($newProfileId) {
		if($newProfileId === null) {
			$this->profileId = null;
			return;
		}

		$newProfileId = filter_var($newProfileId, FILTER_VALIDATE_INT);
		if($newProfileId === false) {
			throw(new InvalidArgumentException("product id is not a valid integer"));
		}

		if($newProfileId <= 0) {
			throw(new RangeException("product id must be positive"));
		}

		$this->profileId = inval($newProfileId);
	}

	/**
	 * accessor for the product name
	 *
	 * @return string value for the product name
	 */
	public function getProductName() {
		return $this->productName;
	}

	/**
	 * mutator for the product name
	 *
	 * @param string value for the product name
	 * @throws RangeException if the product name is too large
	 */
	public function setProductName($newProductName) {
		$newProductName = trim($newProductName);
		$newProductName = filter_var($newProductName, FILTER_SANITIZE_STRING);

		if(strlen($newProductName) > 45) {
			throw(new RangeException("product name is too large"));
		}

		$this->productName = $newProductName;
	}

	/**
	 * accessor for the product type
	 *
	 * @return string value for the product type
	 */
	public function getProductType() {
		return $this->productType;
	}

	/**
	 * mutator for the product type
	 *
	 * @param string value for the product type
	 * @throws RangeException if the product type is too large
	 */
	public function setProductType($newProductType) {
		$newProductType = trim($newProductType);
		$newProductType = filter_var($newProductType, FILTER_SANITIZE_STRING);

		if(strlen($newProductType) > 45) {
			throw(new RangeException("product name is too large"));
		}

		$this->productType = $newProductType;
	}

	/**
	 * accessor for the product price
	 *
	 * @return float value for the product price
	 */
	public function getProductPrice() {
		return $this->productPrice;
	}

	/**
	 * mutator for the product price
	 *
	 * @param float value for the product price
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if $newProductPrice is less than 0
	 */
	public function setProductPrice($newProductPrice) {
		$newProductPrice = filter_var($newProductPrice, FILTER_VALIDATE_FLOAT);
		if($newProductPrice === false) {
			throw(new InvalidArgumentException("product id is not a valid float"));
		}

		$newProductPrice = round($newProductPrice, 4);
		if($newProductPrice > 9999.9999) {
			throw(new RangeException("product price is too large"));
		}

		$this->productPrice = inval($newProductPrice);
	}

	/**
	 * accessor for the product weight
	 *
	 * @return int value for the product wWeight
	 */
	public function getProductWeight() {
		return $this->productWeight;
	}

	/**
	 * mutator for the product weight
	 *
	 * @param int value for the product price
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if $newProductWeight is less than 0
	 */
	public function setProductWeight($newProductWeight) {
		$newProductWeight = filter_var($newProductWeight, FILTER_VALIDATE_FLOAT);
		if($newProductWeight === false) {
			throw(new InvalidArgumentException("product id is not a valid float"));
		}

		$newProductWeight = round($newProductWeight, 4);
		if($newProductWeight > 9999.9999) {
			throw(new RangeException("product weight is too large"));
		}

		$this->productWeight = inval($newProductWeight);
	}

	/**
	 * insert this product id into mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public function insert(&$mysqli) {
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		if($this->productId !== null) {
			throw(new mysqli_sql_exception("not a new product"));
		}

		$query	 = "INSERT INTO product(profileId, productName, productPrice, productType, productWeight) VALUES(?, ?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		$wasClean	  = $statement->bind_param("isdsd", $this->profileId, $this->productName, $this->productPrice,
			$this->productType, $this->productWeight);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		$this->productId = $mysqli->insert_id;
		$statement->close();
	}

	/**
	 * deletes this product from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		if($this->productId === null) {
			throw(new mysqli_sql_exception("unable to delete a product that does not exist"));
		}

		$query	 = "DELETE FROM product WHERE productId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("i", $this->productId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		$statement->close();
	}

	/**
	 * updates this product in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(&$mysqli) {
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		if($this->productId === null) {
			throw(new mysqli_sql_exception("unable to update a product that does not exist"));
		}

		$query	 = "UPDATE product SET profileId = ?, productName = ?, productPrice = ?, productType = ?,
			productWeight = ? WHERE productId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		$wasClean	  = $statement->bind_param("isdsd", $this->profileId, $this->productName, $this->productPrice,
			$this->productType, $this->productWeight);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		$statement->close();
	}


}