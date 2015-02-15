<?php

/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

session_start();

$currentDir = dirname(__FILE__);

// header
require_once '../root-path.php';
require_once '../php/lib/header.php';

?>

<div class="row-fluid">
	<div class="col-sm-12">
		<form id="checkoutShippingController" action="../php/forms/checkout-shipping-controller.php" method="post" onsubmit="event.preventDefault()" novalidate>
			<h2>checkout time! ah ah! See a lot of Stripes here...</h2>
			<br/>
			<p>The chosen location you "have chosen" is:</p>
			<ul>
				<li>Grower's Market</li>
				<li>Robinson Park</li>
				<li>87102, Albuquerque NM</li>
			</ul>

			<p id="outputArea"></p>
			<input type="submit" value="Continue to checkout" class="btn btn-default push-right" id="cart-validate-button">
		</form>
	</div>
</div><!-- end row-fluid -->


<?php require_once '../php/lib/footer.php'; ?>
