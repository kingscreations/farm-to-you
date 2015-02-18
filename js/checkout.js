/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

$(document).ready(function() {

	// This identifies your website in the createToken call below
	Stripe.setPublishableKey('pk_test_jhr3CTTUfUhZceoZrxs5Hpu0');

	/**
	 * form validation and first call to stripe with the createToken function
	 */
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
		}
	});

	/**
	 * stripe asynchronous call
	 *
	 * @param status
	 * @param response
	 */
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

			sendFormData();
		}
	};

	/**
	 * send the data to the server
	 *
	 * data: stripeToken the token from stripe
	 * data: rememberUser the state of the checkbox
	 */
	function sendFormData() {
		var $rememberUserCheckbox = $('#remember-user');
		var data = {
			'stripeToken': $('#stripe-token').val(),
		};
		if($rememberUserCheckbox.is(":checked")) {
			data.rememberUser = $rememberUserCheckbox.val();
		}

		$.ajax({
			type: "post",
			url: "../php/forms/checkout-controller.php",
			data: data
		})
			.done(function(ajaxOutput) {
				$("#outputArea").css('display', '');
				$("#outputArea").html(ajaxOutput);
			});
	}


	/**
	 * enable / disable input and change the text color to light grey
	 * depending on which radio button is checked
	 */
	var $newCard               = $('#new-card');
	var $submit                = $('#new-card button[type=submit]');
	var $newCardInputs         = $('#new-card input');
	var $checkoutRadioRemember = $('#checkout-radio-remember');

	$checkoutRadioRemember.on('click', function() {
		console.log('checkout-radio-remember checked');

		if($(this).is(':checked') && $(this).val() === 'remember') {
			$.each($newCardInputs, function() {
				$input = $(this);
				$input.prop('disabled', true);
				$input.addClass('disable-color');
			});
			$newCard.addClass('disable-color');

			// disable the form validation process
			$submit.addClass('formnovalidate');
		}
	});
	$checkoutRadioRemember.click();

	$('#checkout-radio-new-card').on('click', function() {
		console.log('checkout-radio-new-card checked');

		if($(this).is(':checked') && $(this).val() === 'new') {
			$.each($newCardInputs, function() {
				$input = $(this);
				$input.prop('disabled', false);
				$input.removeClass('disable-color');
			});
			$newCard.removeClass('disable-color');

			// enable the form validation process
			$submit.removeClass('formnovalidate');
		}
	});
});