// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../checkout/");
	}
});

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
	console.log('testValidFields');
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
	F.wait(2000, function(){
		F(".alert").visible(function() {
			// create a regular expression that evaluates the successful text
			var successMessage = 'Payment done.';

			// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
			ok(F(this).hasClass("alert-success"), "successful alert CSS");
			ok(F(this).text() === successMessage, "successful message");
		});
	});
}
//
///**
// * test filling in invalid form data
// **/
function testInvalidFields() {
	console.log('testInvalidFields');
	if(F('#checkout-radio-new-card').length) {
		F('#checkout-radio-new-card').click();
	}

	F('[name="creditCardNumber"]').type(INVALID_CREDITCARDNUMBER);
	F('[name="cardSecurityCode"]').type(INVALID_SECURITYCODE);
	F('[name="cardExpirationMonth"]').type(INVALID_EXPMONTH);
	F('[name="cardExpirationYear"]').type(INVALID_EXPYEAR);

	// submit the form
	F("#validate-payment").click();

	// output assertions
	F.wait(2000, function(){
		F(".alert").visible(function() {
			// create a regular expression that evaluates the successful text
			var successMessage = 'Payment done.';

			// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
			ok(F(this).hasClass("alert-danger"), "successful alert CSS");
		});
	});
}

test("test valid fields", testValidFields);
test("test invalid fields", testInvalidFields);