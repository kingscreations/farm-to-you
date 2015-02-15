/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

// This identifies your website in the createToken call below
Stripe.setPublishableKey('pk_test_jhr3CTTUfUhZceoZrxs5Hpu0');

$(document).ready(function() {

	function stripeResponseHandler(status, response) {
		var $form = $('#payment-form');

		if (response.error) {
			// Show the errors on the form
			$form.find('.payment-errors').text(response.error.message);
			$form.find('button').prop('disabled', false);
		} else {
			// response contains id and card, which contains additional card details
			var token = response.id;
			// Insert the token into the form so it gets submitted to the server
			$form.append($('<input id="stripe-token" type="hidden" />').val(token));

			// send the token to the server
			$.ajax({
				type: "post",
				url: "../php/forms/checkout-controller.php",
				data: {
					'stripeToken': $('#stripe-token').val()
				}
			})
				.done(function(ajaxOutput) {
					$("#outputArea").html(ajaxOutput);
				});
		}
	};

	$('#payment-form').submit(function(event) {
		var $form = $(this);

		// Disable the submit button to prevent repeated clicks
		$form.find('button')
			.prop('disabled', true)
		.addClass('disabled');

		Stripe.card.createToken($form, stripeResponseHandler);

		// Prevent the form from submitting with the default action
		return false;
	});

});