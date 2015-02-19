// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../add-product/index.php");
	}
});

// global variables for form values
var INVALID_PRODUCTNAME  = "Rjtester";
var INVALID_PRODUCTPRICE = "1.200.100492";
var INVALID_PRODUCTDESCRIPTION = "fail plz";
var INVALID_PRODUCTPRICETYPE = "w";
var INVALID_PRODUCTWEIGHT = "4.20";
var INVALID_PRODUCTSTOCKLIMIT = "54";
var INVALID_IMAGE = "yes.txt";

var VALID_PRODUCTNAME    = "Rosemary";
var VALID_PRODUCTPRICE = "1.20";
var VALID_PRODUCTDESCRIPTION = "Description Here";
var VALID_PRODUCTPRICETYPE = "w";
var VALID_PRODUCTWEIGHT = "4.2";
var VALID_PRODUCTSTOCKLIMIT = "52";
var VALID_IMAGE = "images/image.jpg";

/**
 * test filling in only valid form data
 **/
function testValidFields() {
	// fill in the form values
	F("#inputProductName").type(VALID_PRODUCTNAME);
	F("#inputProductPrice").type(VALID_PRODUCTPRICE);
	F("#inputProductDescription").type(VALID_PRODUCTDESCRIPTION);
	F("#inputProductPriceType").click(VALID_PRODUCTPRICETYPE);
	F("#inputProductWeight").type(VALID_PRODUCTWEIGHT);
	F("#inputStockLimit").type(VALID_PRODUCTSTOCKLIMIT);
	F("#inputProductImage").click(VALID_IMAGE);

	// click the button once all the fields are filled in
	F("#inputSubmit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// create a regular expression that evaluates the successful text
		var successRegex = /Product \(id = \d+\) posted!/;

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
	F("#inputProductName").type(INVALID_PRODUCTNAME);
	F("#inputProductPrice").type(INVALID_PRODUCTPRICE);
	F("#inputProductDescription").type(INVALID_PRODUCTDESCRIPTION);
	F("#inputProductPriceType").click(INVALID_PRODUCTPRICETYPE);
	F("#inputProductWeight").type(INVALID_PRODUCTWEIGHT);
	F("#inputStockLimit").type(INVALID_PRODUCTSTOCKLIMIT);
	F("#inputProductImage").click(INVALID_IMAGE);

	// click the button once all the fields are filled in
	F("#inputSubmit").click();

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