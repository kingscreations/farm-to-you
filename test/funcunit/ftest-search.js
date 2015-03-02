// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../index.php");
	}
});

// global variables for form values
var INVALID_SEARCH  = "you will find nothing";


var VALID_SEARCH    = "organic";


/**
 * test filling in only valid form data
 **/
function testValidFields() {
	console.log('testValidFields');
	// fill in the form values
	F("#inputSearch").type(VALID_SEARCH);

	// click the button once all the fields are filled in
	F("#inputSubmit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call

		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		//ok(F(this).hasClass("alert-success"), "successful alert CSS");
		//ok(successRegex.test(F(this).html()), "successful message");



	F.wait(1000, function() {
		ok(F('table').hasClass("table table-responsive table-striped table-hover"), "successful search");
	});
}


/**
 * test filling in invalid form data
 **/
function testInvalidFields() {
	// fill in the form values
	F("#inputSearch").type(INVALID_SEARCH);


	// click the button once all the fields are filled in
	F("#inputSubmit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call

	F.wait(1000, function() {
		F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
			ok(F(this).hasClass("alert-danger"), "danger alert CSS");
			ok(F(this).html().indexOf("Sorry, but we can not find an entry to match your query") === 0, "unsuccessful message");
		});
	});
}

// the test function *MUST* be called in order for the test to execute
test("test valid fields", testValidFields);
test("test invalid fields", testInvalidFields);