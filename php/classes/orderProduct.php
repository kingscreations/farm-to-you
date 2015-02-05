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
}
?>