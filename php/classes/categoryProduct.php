<?php
/**
 * This is the class for the categoryProduct function of farmtoyou
 *
 * @author Jay Renteria <jay@jayrenteria.com>
 **/

class CategoryProduct {
	/**
	 * id for the category, this is part of the composite key
	 */
	private $categoryId;

	/**
	 * id for the product, this is part of the composite key
	 **/
	private $productId;

	/**
	 * constructor for this category product class
	 *
	 *
	 * /**
	 * mutator method for categoryId
	 *
	 * @param int $newCategoryId new value of $categoryId
	 * @throws InvalidArgumentException if the $categoryId is not an integer
	 * @throws RangeException if the $categoryId is not positive
	 **/
	public function setCategoryId($newCategoryId) {
		// verify the category id is valid
		$newCategoryId = filter_var($newCategoryId, FILTER_VALIDATE_INT);
		if($newCategoryId === false) {
			throw(new InvalidArgumentException("category id is not a valid integer"));
		}
		// verify the category id is positive
		if($newCategoryId <= 0) {
			throw(new RangeException("category id is not positive"));
		}
		// convert and store the user id
		$this->categoryId = intval($newCategoryId);
	}

	/**
	 * accessor method for the product Id
	 *
	 * @return int value of product id
	 **/
	public function getProductId() {
		return ($this->productId);
	}

	/**
	 * mutator method for this product Id
	 *
	 * @param int $newProductId new value of $productId
	 * @throws InvalidArgumentException if the $productId is not an integer
	 * @throws RangeException if the $productId is not positive
	 **/
	public function setProductId($newProductId) {
		// verify the product id is valid
		$newProductId = filter_var($newProductId, FILTER_VALIDATE_INT);
		if($newProductId === false) {
			throw(new InvalidArgumentException("product id is not a valid integer"));
		}
		// verify the user id is positive
		if($newProductId <= 0) {
			throw(new RangeException("product id is not positive"));
		}
		// convert and store the user id
		$this->productId = intval($newProductId);
	}
}