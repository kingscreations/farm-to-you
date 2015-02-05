<?php
/**
 * Model to connect with the product entity
 *
 * @author <fgoussin@cnm.edu>
 */
class Product {

	/**
	 * @var int $productId id for the product. This is the primary key of the product entity.
	 */
	private $productId;

	/**
	 * @var int $profileId id for the profile. This is a foreign key to the profile entity.
	 */
	private $profileId;

	/**
	 * @var string $imagePath image path of the product
	 */
	private $imagePath;

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
	 * @param int $newProductId
	 * @param int $newProfileId
	 * @param string $newImagePath
	 * @param string $newProductName
	 * @param string $newProductPrice
	 * @param string $newProductType
	 * @param float $newProductWeight
	 *
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds
	 */
	public function __construct($newProductId, $newProfileId, $newImagePath, $newProductName, $newProductPrice, $newProductType, $newProductWeight) {
		try {
			$this->setProductId($newProductId);
			$this->setProfileId($newProfileId);
			$this->setImagePath($newImagePath);
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
	 * @param int $newProductId for the product id
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

		$this->productId = intval($newProductId);
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
	 * @param int $newProductId for the product id
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

		$this->profileId = intval($newProfileId);
	}

	/**
	 * accessor for the image path
	 *
	 * @return string $imagePath for the image path
	 */
	public function getImagePath() {
		return $this->imagePath;
	}

	/**
	 * mutator for the image path
	 *
	 * @param string $newImagePath for the image path
	 * @internal param string $imagePath for the image path
	 */
	public function setImagePath($newImagePath) {
		$newImagePath = trim($newImagePath);
		$newImagePath = filter_var($newImagePath, FILTER_SANITIZE_STRING);

		if(strlen($newImagePath) > 255) {
			throw(new RangeException("image path is too large"));
		}

		$this->imagePath = $newImagePath;
	}

	/**
	 * accessor for the product name
	 *
	 * @return string value for the image name
	 */
	public function getProductName() {
		return $this->productName;
	}

	/**
	 * mutator for the product name
	 *
	 * @param string $newProductName for the product name
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
	 * @param string $newProductType for the product type
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
			throw(new InvalidArgumentException("product price is not a valid float"));
		}

		$newProductPrice = round($newProductPrice, 4);
		if($newProductPrice > 9999.9999) {
			throw(new RangeException("product price is too large"));
		}

		$this->productPrice = intval($newProductPrice);
	}

	/**
	 * accessor for the product weight
	 *
	 * @return float $productWeight for the product Weight
	 */
	public function getProductWeight() {
		return $this->productWeight;
	}

	/**
	 * mutator for the product weight
	 *
	 * @param float $newProductWeight for the product price
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

		$this->productWeight = intval($newProductWeight);
	}

	/**
	 * insert this product into mySQL
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

		$query	 = "INSERT INTO product(profileId, imagePath, productName, productPrice, productType, productWeight) VALUES(?, ?, ?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		$wasClean	  = $statement->bind_param("issdsd", $this->profileId, $this->imagePath, $this->productName, $this->productPrice,
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

		$query	 = "UPDATE product SET profileId = ?, imagePath = ?, productName = ?, productPrice = ?, productType = ?,
			productWeight = ? WHERE productId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		$wasClean	  = $statement->bind_param("issdsdi", $this->profileId, $this->imagePath, $this->productName, $this->productPrice,
			$this->productType, $this->productWeight, $this->productId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		$statement->close();
	}

	/**
	 * gets the Product by name
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $productName product name to search for
	 * @return mixed array of Products found, Products found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getProductByProductName(&$mysqli, $productName) {
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		$productName = trim($productName);
		$productName = filter_var($productName, FILTER_SANITIZE_STRING);

		$query	 = "SELECT productId, profileId, imagePath, productName, productPrice, productType, productWeight FROM product WHERE productName LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the product name to the place holder in the template
		$productName = "%$productName%";
		$wasClean = $statement->bind_param("s", $productName);
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

		// build an array of product
		$products = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$product	= new Product($row["productId"], $row["profileId"], $row["imagePath"], $row["productName"], $row["productPrice"],
					$row["productType"], $row["productWeight"]);
				$products[] = $product;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		$numberOfProducts = count($products);
		if($numberOfProducts === 0) {
			return(null);
		} else if($numberOfProducts === 1) {
			return($products[0]);
		} else {
			return($products);
		}
	}

	/**
	 * gets the Product by type
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $productType product type to search for
	 * @return mixed array of Products found, Products found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getProductByProductType(&$mysqli, $productType) {
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		$productType = trim($productType);
		$productType = filter_var($productType, FILTER_SANITIZE_STRING);

		$query	 = "SELECT productId, profileId, imagePath, productName, productPrice, productType, productWeight FROM product WHERE productType LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the product type to the place holder in the template
		$productType = "%$productType%";
		$wasClean = $statement->bind_param("s", $productType);
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

		// build an array of product
		$products = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$product	= new Product($row["productId"], $row["profileId"], $row["imagePath"], $row["productName"], $row["productPrice"],
					$row["productType"], $row["productWeight"]);
				$products[] = $product;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		$numberOfProducts = count($products);
		if($numberOfProducts === 0) {
			return(null);
		} else if($numberOfProducts === 1) {
			return($products[0]);
		} else {
			return($products);
		}
	}

	/**
	 * gets the Product by productId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $productId product content to search for
	 * @return mixed Product found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getProductByProductId(&$mysqli, $productId) {
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		$productId = filter_var($productId, FILTER_VALIDATE_INT);
		if($productId === false) {
			throw(new mysqli_sql_exception("product id is not an integer"));
		}
		if($productId <= 0) {
			throw(new mysqli_sql_exception("product id is not positive"));
		}
		$query	 = "SELECT productId, profileId, imagePath, productName, productPrice, productType, productWeight FROM product WHERE productId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("i", $productId);
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

		// grab the product from mySQL
		try {
			$product = null;
			$row   = $result->fetch_assoc();
			if($row !== null) {
				$product	= new Product($row["productId"], $row["profileId"], $row["imagePath"], $row["productName"], $row["productPrice"],
					$row["productType"], $row["productWeight"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
		}

		$result->free();
		$statement->close();
		return($product);
	}

	/**
	 * gets all Products
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @return mixed array of Products found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getAllProducts(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// create query template
		$query	 = "SELECT productId, profileId, productContent, productDate FROM product";
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

		// build an array of product
		$products = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$product	= new Product($row["productId"], $row["profileId"], $row["imagePath"], $row["productName"], $row["productPrice"],
					$row["productType"], $row["productWeight"]);
				$products[] = $product;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}
		$result->free();
		$statement->close();

		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if >= 1 result
		$numberOfProducts = count($products);
		if($numberOfProducts === 0) {
			return(null);
		} else {
			return($products);
		}
	}
}