/**
 * Created by jason on 2/24/2015.
 */
// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../sign-up/index.php");
	}
});

// global variables for form values
var INVALID_EMAIL = "jason@jason.com";
var INVALID_PASSWORD = "password";
var INVALID_PASSWORD2 = "password";

var VALID_EMAIL = "farmer@suspender.com";
var VALID_PASSWORD = "password";
var VALID_PASSWORD2 = "password";
/**
 * test filling in only valid form data
 **/
function testValidFields() {
	// fill in the form values
	F("#inputEmail").type(VALID_EMAIL);
	F("#password").type(VALID_PASSWORD);
	F("#passwordCheck").type(VALID_PASSWORD2);

	// click the button once all the fields are filled in
	F("#submit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// create a regular expression that evaluates the successful text
		var successRegex = /Sign up successful! Please check your Email to complete the signup process./;
		ok(F(this).hasClass("alert-success"), "successful alert CSS");
		ok(successRegex.test(F(this).html()), "successful message");

	});
}

/**
 * test filling in invalid form data
 **/
function testInvalidFields() {

	// delete default form value and fill in the form value
	F("#locationName").type('[ctrl]a[ctrl-up][delete]');
	F("#locationName").type(INVALID_LOCATIONNAME);

	// click the button once field is filled in
	F("#editSubmit").click();

	// assert we got the php error message from the AJAX call
	F(".alert").visible(function() {
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Exception: location name is empty or insecure") === 0, "unsuccessful message");
	});

	//retype a valid input in previous field to prevent other error messages
	F("#locationName").type(VALID_LOCATIONNAME);

	// delete default form value and fill in the form value
	F("#address1").type('[ctrl]a[ctrl-up][delete]');
	F("#address1").type(INVALID_ADDRESS1);

	// click the button once field is filled in
	F("#editSubmit").click();

	// assert we got the php error message from the AJAX call
	F(".alert").visible(function() {
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Exception: address 1 is empty or insecure") === 0, "unsuccessful message");
	});

	//retype a valid input in previous field to prevent other error messages
	F("#address1").type(VALID_ADDRESS1);

	// delete default form value and fill in the form value
	F("#address2").type('[ctrl]a[ctrl-up][delete]');
	F("#address2").type(INVALID_ADDRESS2);
	F("#city").type('[ctrl]a[ctrl-up][delete]');
	F("#city").type(INVALID_CITY);

	// click the button once field is filled in
	F("#editSubmit").click();

	// assert we got the php error message from the AJAX call
	F(".alert").visible(function() {
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Exception: city name is empty or insecure") === 0, "unsuccessful message");
	});

	//retype a valid input in previous field to prevent other error messages
	F("#city").type(VALID_CITY);

	// delete default form value and fill in the form value
	F("#zipCode").type('[ctrl]a[ctrl-up][delete]');
	F("#zipCode").type(INVALID_ZIPCODE);
	F("#country").type('[ctrl]a[ctrl-up][delete]');
	F("#country").type(INVALID_COUNTRY);
	F("#state").type('[ctrl]a[ctrl-up][delete]');
	F("#state").type(INVALID_STATE);

	// click the button once field is filled in
	F("#editSubmit").click();

	// assert we got the php error message from the AJAX call
	F(".alert").visible(function() {
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Exception: state name is empty or insecure") === 0, "unsuccessful message");
	});
}

// the test function *MUST* be called in order for the test to execute
test("test valid fields", testValidFields);
test("test invalid fields", testInvalidFields);