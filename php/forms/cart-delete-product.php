<?php

/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

session_start();

if(!@isset($_POST['product'])) {
	throw new Exception('No product provided!');
} else {
	$deleteProductId = filter_var($_POST['product'], FILTER_SANITIZE_STRING);
	$productId = explode('delete-product-', $deleteProductId)[1];
}

if(count($_SESSION['products']) > 1) {
	unset($_SESSION['products'][$productId]);
} else {
	unset($_SESSION['products']);
}

echo 'product deleted';

?>