// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../edit-store/index.php");
	}
});

// global variables for form values
var INVALID_STORENAME    = "<>";
var INVALID_STOREDESCRIPTION = "fail";
var INVALID_IMAGE = "yes.txt";

var VALID_STORENAME    = "Pass Farms";
var VALID_STOREDESCRIPTION = "Description Here";
var VALID_IMAGE = "images/image.jpg";

/**
 * test filling in only valid form data
 **/
function testValidFields() {
	// fill in the form values
	F("#editStoreName").type('[ctrl]a[ctrl-up][delete]');
	F("#editStoreName").type(VALID_STORENAME);
	F("#editStoreDescription").type('[ctrl]a[ctrl-up][delete]');
	F("#editStoreDescription").type(VALID_STOREDESCRIPTION);
	F("#editInputImage").click(VALID_IMAGE);

	// click the button once all the fields are filled in
	F("#editSubmit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// create a regular expression that evaluates the successful text
		var successRegex = /^[a-zA-Z0-9!?\s_.-]+\supdated!$/;

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
	F("#editStoreName").type('[ctrl]a[ctrl-up][delete]');
	F("#editStoreName").type(INVALID_STORENAME);

	F("#editSubmit").click();
	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Exception: store name is empty or insecure") === 0, "unsuccessful message");
	});

	F("#editStoreName").type(VALID_STORENAME);

	F("#editStoreDescription").type(INVALID_STOREDESCRIPTION);

	F("#editInputImage").click(INVALID_IMAGE);

	F.wait(10000);
	// click the button once all the fields are filled in
	F("#editSubmit").click();
	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Exception: The input image file should be either jpg, JPG, jpeg, JPEG, png or PNG") === 0, "unsuccessful message");
	});
}

// the test function *MUST* be called in order for the test to execute
test("test valid fields", testValidFields);
test("test invalid fields", testInvalidFields);