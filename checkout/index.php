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

<div class="row-fluid" xmlns="http://www.w3.org/1999/html">
	<div class="col-sm-4 col-sm-offset-4">
		<form id="payment-form" action="../php/forms/checkout-controller.php" method="post" onsubmit="event.preventDefault()" novalidate>
			<br/>
			<div class="basic-information">
				<p>The chosen location you "have chosen" is:</p>
				<ul>
					<li>Grower's Market</li>
					<li>Robinson Park</li>
					<li>87102, Albuquerque NM</li>
				</ul>
			</div>

			<h2>Secure payment via stripe</h2>

			<div class="form-row">
				<label>
					<span>Card Number</span>
					<input type="text" name="creditCardNumber" size="20" data-stripe="number"/>
				</label>
			</div>

			<div class="form-row">
				<label>
					<span>CVC</span>
					<input type="text" name="cardSecurityCode" size="4" data-stripe="cvc"/>
				</label>
			</div>

			<div class="form-row">
				<label>
					<span>Expiration (MM/YYYY)</span>
					<input type="text" name="cardExpirationMonth" size="2" data-stripe="exp-month"/>
				</label>
				<span> / </span>
				<input type="text" name="cardExpirationYear" size="4" data-stripe="exp-year"/>
			</div>
			<div class="form-row center">
				<label>
					<input type="checkbox" id="remember-user" />
					<span>Remember my card information for the next time</span>
				</label
			</div>
			<div class="form-row center">
				<button id="validate-payment" type="submit"  class="btn btn-success">Submit Payment</button>
			</div>
			<p id="outputArea"></p>
		</form>
	</div>
</div><!-- end row-fluid -->


<?php require_once '../php/lib/footer.php'; ?>
