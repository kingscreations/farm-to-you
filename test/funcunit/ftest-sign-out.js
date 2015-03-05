/**
 * Created by jason on 3/2/2015.
 */
// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../sign-in/index.php");
	}
});

// global variables for form values

/**
 * test filling in only valid form data
 **/
function testValidFields() {
	// fill in the form values
	F("#email").type(VALID_EMAIL);
	F("#password2").type(VALID_PASSWORD);

	// click the button once all the fields are filled in
	F("#submit").click();

	// click the logout button
	//F("#my-account-dropdown-menu").click();
	F("#sign-out").click();

	// try to access account

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// create a expression that evaluates the successful text
		//ok(F(this).hasClass("alert-success"), "successful alert CSS");
		ok(F(this).html().indexOf("You are now signed out. Thank you for visiting farm-to-you. We hope to see you again soon.") === 0, "successful message");
	});
}


// the test function *MUST* be called in order for the test to execute
test("test valid fields", testValidFields);
