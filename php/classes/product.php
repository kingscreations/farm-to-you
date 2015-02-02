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
	 * @var string $productName name of the product
	 */
	private $productName;

	/**
	 * @var string $productPrice price of the product
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
	 * @var int $profileId id for the profile. This is a foreign key to the profile entity.
	 */
	private $profileId;

	/**
	 * constructor of this product
	 *
	 * @param int productId
	 * @param string $productName
	 * @param string $productPrice
	 * @param string $productType
	 * @param float $productWeight
	 * @param int $profileId
	 *
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds
	 */
	public function __construct() {
		// TODO create the construct content
	}

	/**
	 * accessor for the productId
	 *
	 * @return int value for the productId
	 */
	public function getProductId() {
		return $this->productId;
	}

	/**
	 * mutator for the productId
	 *
	 * @param int value for the productId
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
}