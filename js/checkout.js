/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

$(document).ready(function() {

	/**
	 * enable / disable input and change the text color to light grey
	 * depending on which radio button is checked
	 */
	var newCard               = $('#new-card');
	var submit                = $('#validate-payment');
	var newCardInputs         = $('#new-card input');
	var checkoutRadioRemember = $('#checkout-radio-remember');
	var checkoutRadioNewCard  = $('#checkout-radio-new-card');

	var novalidate = 'formnovalidate';

	// disable the credit card form
	checkoutRadioRemember.on('click', function() {

		if(checkoutRadioRemember.is(':checked') && checkoutRadioRemember.val() === 'old') {

			$.each(newCardInputs, function() {
				$input = $(this);
				$input.prop('disabled', true);
				$input.addClass('disable-color');
			});
			newCard.addClass('disable-color');

			// disable the form validation process
			submit.addClass(novalidate);
		}
	});

	// triggers the click event the first time the program runs
	checkoutRadioRemember.click();

	// enable the credit card form
	checkoutRadioNewCard.on('click', function() {

		if(checkoutRadioNewCard.is(':checked') && checkoutRadioNewCard.val() === 'new') {

			$.each(newCardInputs, function() {
				$input = $(this);
				$input.prop('disabled', false);
				$input.removeClass('disable-color');
			});
			newCard.removeClass('disable-color');

			// enable the form validation process
			submit.removeClass(novalidate);
		}
	});

	$("#payment-form").click(function() {
		console.log('#payment-form clicked!');
	});

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

			if(submit.hasClass(novalidate) === true) {

				// directly send the data from the form
				sendFormData();

			} else {

				// create a new stripe token from the credit card information
				Stripe.card.createToken($form, stripeResponseHandler);
			}
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
			$form.append('<p class=\"alert alert-danger\">Exception: Payment refused</p>');
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
			'stripeToken': $('#stripe-token').val()
		};

		// assign the good value to the credit card
		if(checkoutRadioNewCard.is(':checked')) {
			data.creditCard = checkoutRadioNewCard.val();
		} else {
			data.creditCard = checkoutRadioRemember.val();
		}

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

				setTimeout(function() {
					// refresh the page
					location.href = "../confirmation/index.php";
				}, 1000);


			});
	}

});