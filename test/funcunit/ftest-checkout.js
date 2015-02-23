// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../cart/");
	}
});

var VALID_QUANTITY      = "12";

var $cartQuantities = $('table.table tbody tr [id$=-quantity]');

// global variables for form values

var VALID_CREDITCARDNUMBER   = "378282246310005";
var VALID_SECURITYCODE       = "123";
var VALID_EXPMONTH           = "12";
var VALID_EXPYEAR            = "2019";


var INVALID_CREDITCARDNUMBER   = "0000000000000000";
var INVALID_SECURITYCODE       = "123";
var INVALID_EXPMONTH           = "12";
var INVALID_EXPYEAR            = "2019";

/**
 * test filling in only valid form data
 **/
function testValidFields() {

	// fill in the cart
	$cartQuantities.val(12);

	// click the button once all the fields are filled in
	F("#cart-validate-button").click();

	F.wait(2000, function(){
		F('#checkout-shipping-submit').click()

		F.wait(2000, function() {
			if(F('#checkout-radio-new-card').length) {
				F('#checkout-radio-new-card').click();
			}

			F('[name="creditCardNumber"]').type(VALID_CREDITCARDNUMBER);
			F('[name="cardSecurityCode"]').type(VALID_SECURITYCODE);
			F('[name="cardExpirationMonth"]').type(VALID_EXPMONTH);
			F('[name="cardExpirationYear"]').type(VALID_EXPYEAR);

			// submit the form
			F("#validate-payment").click();

			// output assertions
			F.wait(2000, function() {
				F(".alert").visible(function() {
					// create a regular expression that evaluates the successful text
					var successMessage = 'Payment done.';

					// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
					ok(F(this).hasClass("alert-success"), "successful alert CSS");
					ok(F(this).text() === successMessage, "successful message");
				});
			});
		});
	});
}

///**
// * test filling in invalid form data
// **/
function testInvalidFields() {

	// fill in the cart
	$cartQuantities.val(12);

	// click the button once all the fields are filled in
	F("#cart-validate-button").click();

	F.wait(2000, function(){
		F('#checkout-shipping-submit').click()

		F.wait(2000, function() {
			if(F('#checkout-radio-new-card').length) {
				F('#checkout-radio-new-card').click();
			}

			F('[name="creditCardNumber"]').type(INVALID_CREDITCARDNUMBER);
			F('[name="cardSecurityCode"]').type(INVALID_SECURITYCODE);
			F('[name="cardExpirationMonth"]').type(INVALID_EXPMONTH);
			F('[name="cardExpirationYear"]').type(INVALID_EXPYEAR);

			// submit the form
			F("#validate-payment").click();

			//output assertions
			F.wait(2000, function(){
				F(".alert").visible(function() {
					// create a regular expression that evaluates the successful text
					var successMessage = 'Payment done.';

					// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
					ok(F(this).hasClass("alert-danger"), "successful alert CSS");
				});
			});
		});
	});
}

/**
* test filling in only valid form data AND remember the user
**/
function testValidFieldsAndRememberUser() {
	// fill in the cart
	$cartQuantities.val(12);

	// click the button once all the fields are filled in
	F("#cart-validate-button").click();

	F.wait(2000, function(){
		F('#checkout-shipping-submit').click()

		F.wait(2000, function() {
			if(F('#checkout-radio-new-card').length) {
				F('#checkout-radio-new-card').click();
			}

			F('[name="creditCardNumber"]').type(VALID_CREDITCARDNUMBER);
			F('[name="cardSecurityCode"]').type(VALID_SECURITYCODE);
			F('[name="cardExpirationMonth"]').type(VALID_EXPMONTH);
			F('[name="cardExpirationYear"]').type(VALID_EXPYEAR);

			/////////////////////////////////
			// choose to remember the user
			/////////////////////////////////
			F('#remember-user').click();

			// submit the form
			F("#validate-payment").click();

			// output assertions
			F.wait(2000, function() {
				F(".alert").visible(function() {
					// create a regular expression that evaluates the successful text
					var successMessage = 'Payment done.';

					// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
					ok(F(this).hasClass("alert-success"), "successful alert CSS");
					ok(F(this).text() === successMessage, "successful message");
				});
			});
		});
	});
}

/**
* test submitting the old credit card
**/
function testSubmitOldCreditCard() {
	// fill in the cart
	$cartQuantities.val(12);

	// click the button once all the fields are filled in
	F("#cart-validate-button").click();

	F.wait(2000, function(){
		F('#checkout-shipping-submit').click()

		F.wait(2000, function() {
			if(F('#checkout-radio-remember').length) {
				F('#checkout-radio-remember').click();
			}

			// submit the form
			F("#validate-payment").click();

			// output assertions
			F.wait(2000, function() {
				F(".alert").visible(function() {
					// create a regular expression that evaluates the successful text
					var successMessage = 'Payment done.';

					// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
					ok(F(this).hasClass("alert-success"), "successful alert CSS");
					ok(F(this).text() === successMessage, "successful message");
				});
			});
		});
	});
}

test("test valid fields", testValidFields);
test("test invalid fields", testInvalidFields);
test("test valid fields and remember the user", testValidFieldsAndRememberUser);
test("test submitting the old credit card", testSubmitOldCreditCard);