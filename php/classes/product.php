<?php
/**
 * Model to connect with the product entity
 *
 * @author <fgoussin@cnm.edu>
 */
class Product {

	/**
	 * @var mixed $productId id for the product. This is the primary key of the product entity.
	 */
	private $productId;

	/**
	 * @var int $storeId id for the store. This is a foreign key to the store entity.
	 */
	private $storeId;

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
	 * @var float $productPriceType product price type
	 */
	private $productPriceType;

	/**
	 * @var string $productDescription description of the product
	 */
	private $productDescription;

	/**
	 * @var float $productWeight weight of the product
	 */
	private $productWeight;

	/**
	 * @var int $stockLimit maximum number of this product the merchant can or want to sell
	 */
	private $stockLimit;


	/**
	 * constructor of this product
	 *
	 * @param mixed $newProductId id for the product
	 * @param int $newStoreId id for the store
	 * @param string $newImagePath image path of the product
	 * @param string $newProductName product name
	 * @param string $newProductPrice product price
	 * @param string $newProductDescription product description
	 * @param string $newProductPriceType product price type
	 * @param float $newProductWeight product weight
	 * @param int $newStockLimit limit the number of this product
	 *
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds
	 */
	public function __construct($newProductId, $newStoreId, $newImagePath, $newProductName, $newProductPrice,
										 $newProductDescription, $newProductPriceType, $newProductWeight, $newStockLimit=null) {
		try {
			$this->setProductId($newProductId);
			$this->setStoreId($newStoreId);
			$this->setImagePath($newImagePath);
			$this->setProductName($newProductName);
			$this->setProductPrice($newProductPrice);
			$this->setProductDescription($newProductDescription);
			$this->setProductPriceType($newProductPriceType);
			$this->setProductWeight($newProductWeight);
			$this->setStockLimit($newStockLimit);
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
	 * accessor for the store id
	 *
	 * @return int value for the store id
	 */
	public function getStoreId() {
		return $this->storeId;
	}

	/**
	 * mutator for the store id
	 *
	 * @param int $newStoreId for the store id
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if $newStoreId is less than 0
	 */
	public function setStoreId($newStoreId) {

		$newStoreId = filter_var($newStoreId, FILTER_VALIDATE_INT);
		if($newStoreId === false) {
			throw(new InvalidArgumentException("store id is not a valid integer"));
		}

		if($newStoreId <= 0) {
			throw(new RangeException("store id must be positive"));
		}

		$this->storeId = intval($newStoreId);
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
	 * accessor for the product description
	 *
	 * @return string value for the product description
	 */
	public function getProductDescription() {
		return $this->productDescription;
	}

	/**
	 * mutator for the product description
	 *
	 * @param string $newProductDescription for the product description
	 */
	public function setProductDescription($newProductDescription) {
		$newProductDescription = trim($newProductDescription);
		$newProductDescription = filter_var($newProductDescription, FILTER_SANITIZE_STRING);

		$this->productDescription = $newProductDescription;
	}

	/**
	 * accessor for the product price type
	 *
	 * @return string value for the product price type
	 */
	public function getProductPriceType() {
		return $this->productPriceType;
	}

	/**
	 * mutator for the product price type
	 *
	 * @param string $newProductPriceType for the product price type
	 * @throws RangeException if the product price type is too large
	 */
	public function setProductPriceType($newProductPriceType) {
		$newProductPriceType = trim($newProductPriceType);
		$newProductPriceType = filter_var($newProductPriceType, FILTER_SANITIZE_STRING);

		if(strlen($newProductPriceType) !== 1) {
			throw(new RangeException("product price type length must equal 1"));
		}

		// product price type must be either w for weight or u for unit
		$allowedLetters = ['w', 'u'];
		if(in_array($newProductPriceType, $allowedLetters) === false) {
			throw(new RangeException("product price type must be w or u"));
		}
//		var_dump($newProductPriceType);
		$this->productPriceType = $newProductPriceType;
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

		$this->productPrice = floatval($newProductPrice);
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
		if($newProductWeight === null) {
			$this->productWeight = null;
			return;
		}

		$newProductWeight = filter_var($newProductWeight, FILTER_VALIDATE_FLOAT);
		if($newProductWeight === false) {
			throw(new InvalidArgumentException("the weight is not a valid float"));
		}

		$newProductWeight = round($newProductWeight, 4);
		if($newProductWeight > 9999.9999) {
			throw(new RangeException("product weight is too large"));
		}

		$this->productWeight = floatval($newProductWeight);
	}

	/**
	 * accessor for the stockLimit
	 *
	 * @return int value for the stockLimit
	 */
	public function getStockLimit() {
		return $this->stockLimit;
	}

	/**
	 * mutator for the stockLimit
	 *
	 * @param int $newStockLimit for the product
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if $newStockLimit is less than 0
	 */
	public function setStockLimit($newStockLimit) {
		if($newStockLimit === null) {
			$this->stockLimit = null;
			return;
		}

		$newStockLimit = filter_var($newStockLimit, FILTER_VALIDATE_INT);
		if($newStockLimit === false) {
			throw(new InvalidArgumentException("product id is not a valid integer"));
		}

		if($newStockLimit <= 0) {
			throw(new RangeException("product id must be positive"));
		}

		$this->stockLimit = intval($newStockLimit);
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

		$query	 = "INSERT INTO product(storeId, imagePath, productName, productPrice, productDescription, productPriceType, productWeight, stockLimit) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		$wasClean	  = $statement->bind_param("issdssdi", $this->storeId, $this->imagePath, $this->productName, $this->productPrice,
			$this->productDescription, $this->productPriceType, $this->productWeight, $this->stockLimit);
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

		$query	 = "UPDATE product SET storeId = ?, imagePath = ?, productName = ?, productPrice = ?, productDescription = ?,
			productWeight = ?, stockLimit = ? WHERE productId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		$wasClean	  = $statement->bind_param("issdsdii", $this->storeId, $this->imagePath, $this->productName, $this->productPrice,
			$this->productDescription, $this->productWeight, $this->stockLimit, $this->productId);
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

		$query	 = "SELECT productId, storeId, imagePath, productName, productPrice, productDescription, productPriceType,
 			productWeight, stockLimit FROM product WHERE productName LIKE ?";
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
				$product	= new Product($row["productId"], $row["storeId"], $row["imagePath"], $row["productName"],
					$row["productPrice"], $row["productDescription"], $row["productPriceType"], $row["productWeight"], $row["stockLimit"]);
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
	 * gets the Product by description
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $productDescription product description to search for
	 * @return mixed array of Products found, Products found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getProductByProductDescription(&$mysqli, $productDescription) {
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		$productDescription = trim($productDescription);
		$productDescription = filter_var($productDescription, FILTER_SANITIZE_STRING);

		$query	 = "SELECT productId, storeId, imagePath, productName, productPrice, productDescription, productPriceType,
			productWeight, stockLimit FROM product WHERE productDescription LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the product description to the place holder in the template
		$productDescription = "%$productDescription%";
		$wasClean = $statement->bind_param("s", $productDescription);
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
				$product	= new Product($row["productId"], $row["storeId"], $row["imagePath"], $row["productName"], $row["productPrice"],
					$row["productDescription"], $row['productPriceType'], $row["productWeight"], $row["stockLimit"]);
				$products[] = $product;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		$result->free();
		$statement->close();

		$numberOfProducts = count($products);
		if($numberOfProducts === 0) {
			return(null);
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
		$query	 = "SELECT productId, storeId, imagePath, productName, productPrice, productDescription, productPriceType,
 			productWeight, stockLimit FROM product WHERE productId = ?";
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
				$product	= new Product($row["productId"], $row["storeId"], $row["imagePath"], $row["productName"],
					$row["productPrice"], $row["productDescription"], $row['productPriceType'], $row["productWeight"], $row["stockLimit"]);
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
		$query	 = "SELECT productId, storeId, imagePath, productName, productPrice, productDescription, productPriceType,
 			productWeight, stockLimit FROM product";
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
				$product	= new Product($row["productId"], $row["storeId"], $row["imagePath"], $row["productName"],
					$row["productPrice"], $row["productDescription"], $row['productPriceType'], $row["productWeight"], $row["stockLimit"]);
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