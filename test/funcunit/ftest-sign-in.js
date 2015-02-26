/**
 * Created by jason on 2/26/2015.
 */
// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../sign-in/index.php");
	}
});

// global variables for form values
var INVALID_EMAIL = "big@dog.com";
var INVALID_PASSWORD = "password";

var VALID_EMAIL = "jason@jason.com";
var VALID_PASSWORD = "jason";
/**
 * test filling in only valid form data
 **/
function testValidFields() {
	// fill in the form values
	F("#email").type(VALID_EMAIL);
	F("#password2").type(VALID_PASSWORD);

	// click the button once all the fields are filled in
	F("#submit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// create a expression that evaluates the successful text
		ok(F(this).hasClass("alert-success"), "successful alert CSS");
		ok(F(this).html().indexOf("You are logged in!") === 0, "successful message");
	});
}

/**
 * test filling in invalid form data
 **/
function testInvalidFields() {

	// delete default form value and fill in the form value
	F("#email").type(INVALID_EMAIL);
	F("#password2").type(INVALID_PASSWORD);

	// click the button once field is filled in
	F("#submit").click();

	// assert we got the php error message from the AJAX call
	F(".alert").visible(function() {
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Exception: unable to execute mySQL statement: Duplicate entry") === 0, "unsuccessful message");
	});

}

// the test function *MUST* be called in order for the test to execute
test("test valid fields", testValidFields);
test("test invalid fields", testInvalidFields);
