// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../edit-product/index.php");
	}
});

// global variables for form values
var INVALID_PRODUCTNAME    = "R";
var INVALID_PRODUCTPRICE = "1.2999.0000";
var INVALID_PRODUCTDESCRIPTION = "please fail";
var INVALID_PRODUCTPRICETYPE = "w";
var INVALID_PRODUCTWEIGHT = "4.20";
var INVALID_PRODUCTSTOCKLIMIT = "56";
var INVALID_IMAGE = "yes.txt";

var VALID_PRODUCTNAME    = "Rosemary";
var VALID_PRODUCTPRICE = " ";
var VALID_PRODUCTDESCRIPTION = "Description Here";
var VALID_PRODUCTPRICETYPE = "w";
var VALID_PRODUCTWEIGHT = " ";
var VALID_PRODUCTSTOCKLIMIT = "52";
var VALID_IMAGE = "images/image.jpg";

/**
 * test filling in only valid form data
 **/
function testValidFields() {
	// fill in the form values
	F("#editProductName").type(VALID_PRODUCTNAME);
	F("#editProductPrice").type(VALID_PRODUCTPRICE);
	F("#editProductDescription").type(VALID_PRODUCTDESCRIPTION);
	F("#editProductPriceType").click(VALID_PRODUCTPRICETYPE);
	F("#editProductWeight").type(VALID_PRODUCTWEIGHT);
	F("#editStockLimit").type(VALID_PRODUCTSTOCKLIMIT);
	F("#editProductImage").click(VALID_IMAGE);

	F.wait(10000);
	// click the button once all the fields are filled in
	F("#editSubmit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// create a regular expression that evaluates the successful text
		var successRegex = /Product \(id = \d+\) updated!/;

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
	F("#editProductName").type(INVALID_PRODUCTNAME);
	F("#editProductPrice").type(INVALID_PRODUCTPRICE);
	F("#editProductDescription").type(INVALID_PRODUCTDESCRIPTION);
	F("#editProductPriceType").click(INVALID_PRODUCTPRICETYPE);
	F("#editProductWeight").type(INVALID_PRODUCTWEIGHT);
	F("#editStockLimit").type(INVALID_PRODUCTSTOCKLIMIT);
	F("#editProductImage").click(INVALID_IMAGE);

	F.wait(10000);
	// click the button once all the fields are filled in
	F("#editSubmit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Exception: product price is not a valid float") === 0, "unsuccessful message");
	});
}

// the test function *MUST* be called in order for the test to execute
test("test valid fields", testValidFields);
test("test invalid fields", testInvalidFields);