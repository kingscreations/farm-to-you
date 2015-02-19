<?php

/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

session_start();
//var_dump($_SESSION);
$currentDir = dirname(__FILE__);

// header
require_once '../root-path.php';
require_once '../php/lib/header.php';

// model
require_once('../php/classes/profile.php');

// connexion configuration
mysqli_report(MYSQLI_REPORT_STRICT);

// credentials
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// get the credentials information from the server
$configFile = "/etc/apache2/capstone-mysql/farmtoyou.ini";

?>

<div class="row-fluid" xmlns="http://www.w3.org/1999/html">
	<div class="col-sm-4 col-sm-offset-4">
<!--		<div class="basic-information">-->
<!--			<p>The chosen location you "have chosen" is:</p>-->
<!--			<ul>-->
<!--				<li>Grower's Market</li>-->
<!--				<li>Robinson Park</li>-->
<!--				<li>87102, Albuquerque NM</li>-->
<!--			</ul>-->
<!--		</div>-->
<!--		<br/>-->

		<form id="payment-form" action="../php/forms/checkout-controller.php" method="post" onsubmit="event.preventDefault()" novalidate>
			<h2>Secure payment via stripe</h2>

			<br>
			<?php

			try {
				// connection
				$configArray = readConfig($configFile);
				$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"],
					$configArray["database"]);

			} catch (Exception $exception) {
				echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
			}

			// get the active profile
//			$profile = Profile::getProfileByProfileId($mysqli, $_SESSION['profile']['id']);
			$profile = Profile::getProfileByProfileId($mysqli, 2);

			if($profile === null) {
				throw new Exception('Problem with the SESSION: profile is null');
			}

			// get and test the customer token
			$customerToken = $profile->getCustomerToken();

			if($customerToken !== null && strpos($customerToken, 'cus_') !== false) {
				echo '<input type="radio" id="checkout-radio-remember" name="creditCard" value="old" checked> Use your previous credit card';
				echo '<br/>';
				echo '<input type="radio" id="checkout-radio-new-card" name="creditCard" value="new"> Use another credit card';
			}

			?>

			<div id="new-card">
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
					<input type="checkbox" id="remember-user" name="rememberUser" value="Yes" />
					<label for="remember-user">Remember my card information for the next time</label>
				</div>
			</div><!-- end new-card -->

			<div class="form-row center">
				<button id="validate-payment" type="submit"  class="btn btn-success">Submit Payment</button>
			</div>
			<p id="outputArea"></p>
		</form>
	</div>
</div><!-- end row-fluid -->


<?php require_once '../php/lib/footer.php'; ?>
