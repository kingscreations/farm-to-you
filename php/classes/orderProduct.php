<?php
/**
 * Model to connect with the orderProductProduct weak entity
 *
 * @author <fgoussin@cnm.edu>
 */
class OrderProduct {

	/**
	 * @var int $orderProductId the id of the order. Foreign Key to the order entity
	 */
	private $orderId;

	/**
	 * @var int $productId the id of the product. Foreign Key to the product entity
	 */
	private $productId;

	/**
	 * @var int $productQuantity how many products for this order
	 */
	private $productQuantity;

	/**
	 * constructor of this orderProduct
	 *
	 * @param int $newOrderId
	 * @param int $newProductId
	 * @param string $newProductQuantity
	 *
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds
	 */
	public function __construct($newOrderId, $newProductId, $newProductQuantity) {
		try {
			$this->setOrderId($newOrderId);
			$this->setProductId($newProductId);
			$this->setProductQuantity($newProductQuantity);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor for the order id
	 *
	 * @return int value for the order id
	 */
	public function getOrderId() {
		return $this->orderId;
	}

	/**
	 * mutator for the order id
	 *
	 * @param int $newOrderId for the order id
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if $newOrderId is less than 0
	 */
	public function setOrderId($newOrderId) {
		$newOrderId = filter_var($newOrderId, FILTER_VALIDATE_INT);
		if($newOrderId === false) {
			throw(new InvalidArgumentException("order id is not a valid integer"));
		}

		if($newOrderId <= 0) {
			throw(new RangeException("order id must be positive"));
		}

		$this->orderId = intval($newOrderId);
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
	 * accessor for the product quantity
	 *
	 * @return int $productQuantity for the product quantity
	 */
	public function getProductQuantity() {
		return $this->productQuantity;
	}

	/**
	 * mutator for the product quantity
	 *
	 * @param int $newProductQuantity for the product quantity
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if $newProductId is less than 0
	 */
	public function setProductQuantity($newProductQuantity) {
		$newProductQuantity = filter_var($newProductQuantity, FILTER_VALIDATE_INT);
		if($newProductQuantity === false) {
			throw(new InvalidArgumentException("product quantity is not a valid integer"));
		}

		if($newProductQuantity <= 0) {
			throw(new RangeException("product quantity must be positive"));
		}

		$this->productQuantity = intval($newProductQuantity);
	}

	/**
	 * insert this orderProduct into mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public function insert(&$mysqli) {
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		$query	 = "INSERT INTO orderProduct(orderId, productId, productQuantity) VALUES(?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		$wasClean	  = $statement->bind_param("iii", $this->orderId, $this->productId, $this->productQuantity);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		$statement->close();
	}

	/**
	 * get the order product by the product id
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public function getOrderProductByOrderIdAndProductId(&$mysqli, $orderId, $productId) {
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		$orderId = filter_var($orderId, FILTER_VALIDATE_INT);
		if($orderId === false) {
			throw(new mysqli_sql_exception("order id is not an integer"));
		}
		if($orderId <= 0) {
			throw(new mysqli_sql_exception("order id is not positive"));
		}

		$productId = filter_var($productId, FILTER_VALIDATE_INT);
		if($productId === false) {
			throw(new mysqli_sql_exception("product id is not an integer"));
		}
		if($productId <= 0) {
			throw(new mysqli_sql_exception("product id is not positive"));
		}

		$query	 = "SELECT orderId, productId, productQuantity FROM orderProduct WHERE orderId = ? AND productId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("ii", $orderId, $productId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

		// grab the order from mySQL
		try {
			$orderProduct = null;
			$row   = $result->fetch_assoc();
			if($row !== null) {
				$orderProduct	= new OrderProduct($row["orderId"], $row["productId"], $row["productQuantity"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
		}

		// free up memory and return the result
		$result->free();
		$statement->close();
		return($orderProduct);
	}
}
?>