// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../add-store/index.php");
	}
});

// global variables for form values
var INVALID_STORENAME    = "<>";
var INVALID_STOREDESCRIPTION = "fail";
var INVALID_IMAGE = "yes.txt";
var INVALID_LOCATIONNAME = "<>";
var INVALID_ADDRESS1 = "<>";
var INVALID_ADDRESS2 = "Apt. 2";
var INVALID_CITY = "<>";
var INVALID_STATE = "<>";
var INVALID_ZIPCODE = "87048";
var INVALID_COUNTRY = "US";

var VALID_STORENAME    = "Pass Farms";
var VALID_STOREDESCRIPTION = "Description Here";
var VALID_IMAGE = "images/image.jpg";
var VALID_LOCATIONNAME = "Pass Farms";
var VALID_ADDRESS1 = "1228 W La Entrada";
var VALID_ADDRESS2 = "Apt. 2";
var VALID_CITY = "Corrales";
var VALID_STATE = "NM";
var VALID_ZIPCODE = "87048";
var VALID_COUNTRY = "US";
/**
 * test filling in only valid form data
 **/
function testValidFields() {
	// fill in the form values
	F("#storeName").type('[ctrl]a[ctrl-up][delete]');
	F("#storeName").type(VALID_STORENAME);
	F("#storeDescription").type('[ctrl]a[ctrl-up][delete]');
	F("#storeDescription").type(VALID_STOREDESCRIPTION);
	F("#inputImage").click(VALID_IMAGE);
	F.wait(10000);
	F("#locationName").type('[ctrl]a[ctrl-up][delete]');
	F("#locationName").type(VALID_LOCATIONNAME);
	F("#address1").type('[ctrl]a[ctrl-up][delete]');
	F("#address1").type(VALID_ADDRESS1);
	F("#address2").type('[ctrl]a[ctrl-up][delete]');
	F("#address2").type(VALID_ADDRESS2);
	F("#city").type('[ctrl]a[ctrl-up][delete]');
	F("#city").type(VALID_CITY);
	F("#state").type('[ctrl]a[ctrl-up][delete]');
	F("#state").type(VALID_STATE);
	F("#zipCode").type('[ctrl]a[ctrl-up][delete]');
	F("#zipCode").type(VALID_ZIPCODE);
	F("#country").type('[ctrl]a[ctrl-up][delete]');
	F("#country").type(VALID_COUNTRY);

	// click the button once all the fields are filled in
	F("#editSubmit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// create a regular expression that evaluates the successful text
		var successRegex = /^[a-zA-Z0-9!?\s_.-]+\sadded!$/;
		ok(F(this).hasClass("alert-success"), "successful alert CSS");
		ok(successRegex.test(F(this).html()), "successful message");

		var successRegex2 = /^[a-zA-Z0-9!?\s_.-]+\sadded!$/;
		ok(F(this).hasClass("alert-success"), "successful alert CSS");
		ok(successRegex2.test(F(this).html()), "successful message");

	});
}

/**
 * test filling in invalid form data
 **/
function testInvalidFields() {
	// fill in the form values

	F("#storeName").type('[ctrl]a[ctrl-up][delete]');
	F("#storeName").type(INVALID_STORENAME);

	F("#editSubmit").click();
	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Exception: store name is empty or insecure") === 0, "unsuccessful message");
	});

	F("#storeName").type(VALID_STORENAME);

	F("#storeDescription").type(INVALID_STOREDESCRIPTION);

	F("#inputImage").click(INVALID_IMAGE);

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


	F("#locationName").type('[ctrl]a[ctrl-up][delete]');
	F("#locationName").type(INVALID_LOCATIONNAME);

	F("#editSubmit").click();

	F(".alert").visible(function() {
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Exception: location name is empty or insecure") === 0, "unsuccessful message");
	});

	F("#locationName").type(VALID_LOCATIONNAME);

	F("#address1").type('[ctrl]a[ctrl-up][delete]');
	F("#address1").type(INVALID_ADDRESS1);

	F("#editSubmit").click();

	F(".alert").visible(function() {
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Exception: address 1 is empty or insecure") === 0, "unsuccessful message");
	});

	F("#address1").type(VALID_ADDRESS1);

	F("#address2").type('[ctrl]a[ctrl-up][delete]');
	F("#address2").type(INVALID_ADDRESS2);
	F("#city").type('[ctrl]a[ctrl-up][delete]');
	F("#city").type(INVALID_CITY);

	F("#editSubmit").click();

	F(".alert").visible(function() {
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Exception: city name is empty or insecure") === 0, "unsuccessful message");
	});

	F("#city").type(VALID_CITY);

	F("#zipCode").type('[ctrl]a[ctrl-up][delete]');
	F("#zipCode").type(INVALID_ZIPCODE);
	F("#country").type('[ctrl]a[ctrl-up][delete]');
	F("#country").type(INVALID_COUNTRY);
	F("#state").type('[ctrl]a[ctrl-up][delete]');
	F("#state").type(INVALID_STATE);

	// click the button once all the fields are filled in
	F("#editSubmit").click();
	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Exception: state name is empty or insecure") === 0, "unsuccessful message");
	});
}

// the test function *MUST* be called in order for the test to execute
test("test valid fields", testValidFields);
test("test invalid fields", testInvalidFields);