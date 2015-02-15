/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

$(document).ready(function() {

	// This identifies your website in the createToken call below
	Stripe.setPublishableKey('pk_test_jhr3CTTUfUhZceoZrxs5Hpu0');

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
		}
	};

	$("#payment-form").validate({
		errorClass: "label-danger",
		errorLabelContainer: "#outputArea",
		wrapper: "li",

		rules: {
			creditCardNumber: {
				number: true,
				rangelength: [12, 19],
				required: true
			},
			cardSecurityCode: {
				number: true,
				rangelength: [3, 4],
				required: true
			},
			cardExpirationMonth: {
				number: true,
				exactlength: 2,
				required: true
			},
			cardExpirationYear: {
				number: true,
				exactlength: 4,
				required: true
			}
		},
		messages: {
			creditCardNumber: {
				rangelength: "Your credit card number must be composed at least 12 digits length and at most 19 digits length.",
				required: "Please enter your credit card number."
			},
			cardSecurityCode: {
				rangelength: "Your credit card security code must be composed at least 3 digits length and at most 4 digits length.",
				required: "Please enter your card security code."
			},
			cardExpirationMonth: {
				exactlength: "Your credit card expiration month must be composed of 2 digits.",
				required: "Please enter your card expiration month."
			},
			cardExpirationYear: {
				exactlength: "Your credit card expiration year must be composed of 4 digits.",
				required: "Please enter your card expiration year."
			}
		},

		submitHandler: function(form) {
			var $form = $(form);

			// Disable the submit button to prevent repeated clicks
			$form.find('button')
				.prop('disabled', true)
				.addClass('disabled');

			Stripe.card.createToken($form, stripeResponseHandler);

			// Prevent the form from submitting with the default action
			//return false;

			// send the token to the server
			$.ajax({
				type: "post",
				url: "../php/forms/checkout-controller.php",
				data: {
					'stripeToken': $('#stripe-token').val()
				}
			})
				.done(function(ajaxOutput) {
					console.log('ajaxOutput:');
					console.log(ajaxOutput);
					$("#outputArea").css('display', '');
					$("#outputArea").html(ajaxOutput);
				});
		}
	});

});