// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../edit-profile/index.php");
	}
});

// global variables for form values
var INVALID_PROFILEFNAME    = "Jsy";
var INVALID_PROFILELNAME = "Rreterasd";
var INVALID_PHONE = "459283423495823492348592348235923482349";
var INVALID_IMAGE = "test.txt";

var VALID_PROFILEFNAME     = "Jay";
var VALID_PROFILELNAME   = "Renteria";
var VALID_PHONE = "505-994-3954";
var VALID_IMAGE = "images/image.jpg";

/**
 * test filling in only valid form data
 **/
function testValidFields() {
	// fill in the form values
	F("#inputFirstname").type(VALID_PROFILEFNAME);
	F("#inputLastname").type(VALID_PROFILELNAME);
	F("#inputPhone").type(VALID_PHONE);
	F("#inputImage").click(VALID_IMAGE);

	F.wait(10000);
	// click the button once all the fields are filled in
	F("#inputSubmit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// create a regular expression that evaluates the successful text
		var successRegex = /Profile \(id = \d+\) updated!/;

		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-success"), "successful alert CSS");
		ok(successRegex.test(F(this).html()), "successful message");
	});
}

/**
 * test filling in invalid form data
 **/
function testInvalidFields() {
	// fill in the form values
	F("#inputFirstname").type(INVALID_PROFILEFNAME);
	F("#inputLastname").type(INVALID_PROFILELNAME);
	F("#inputPhone").type(INVALID_PHONE);
	F("#inputImage").click(INVALID_IMAGE);

	F.wait(10000);
	// click the button once all the fields are filled in
	F("#inputSubmit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Exception: The input image file should be either jpg, JPG, jpeg, or JPEG") === 0, "unsuccessful message");
	});
}

// the test function *MUST* be called in order for the test to execute
test("test valid fields", testValidFields);
test("test invalid fields", testInvalidFields);