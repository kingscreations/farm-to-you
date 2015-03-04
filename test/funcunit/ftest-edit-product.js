// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../edit-product/index.php");
	}
});

// global variables for form values
var INVALID_PRODUCTNAME    = "Rosemary";
var INVALID_PRODUCTPRICE = "1.2999.0000";
var INVALID_PRODUCTDESCRIPTION = "please fail";
var INVALID_PRODUCTPRICETYPE = "w";
var INVALID_PRODUCTWEIGHT = "4.20";
var INVALID_PRODUCTSTOCKLIMIT = "56";
var INVALID_IMAGE = "yes.txt";

var VALID_PRODUCTNAME    = "Rosemary";
var VALID_PRODUCTPRICE = "1.00";
var VALID_PRODUCTDESCRIPTION = "Description Here";
var VALID_PRODUCTPRICETYPE = "w";
var VALID_PRODUCTWEIGHT = "1";
var VALID_PRODUCTSTOCKLIMIT = "52";
var VALID_IMAGE = "images/image.jpg";
var VALID_TAG1 = "organic";
var VALID_TAG2 = "";
var VALID_TAG3 = "";
var VALID_TAG4 = "";


/**
 * test filling in only valid form data
 **/
function testValidFields() {
	// fill in the form values
	F("#editProductName").type('[ctrl]a[ctrl-up][delete]');
	F("#editProductName").type(VALID_PRODUCTNAME);
	F("#editProductPrice").type('[ctrl]a[ctrl-up][delete]');
	F("#editProductPrice").type(VALID_PRODUCTPRICE);
	F("#editProductDescription").type('[ctrl]a[ctrl-up][delete]');
	F("#editProductDescription").type(VALID_PRODUCTDESCRIPTION);
	F("#editProductPriceType").click(VALID_PRODUCTPRICETYPE);
	F("#editProductWeight").type('[ctrl]a[ctrl-up][delete]');
	F("#editProductWeight").type(VALID_PRODUCTWEIGHT);
	F("#editStockLimit").type('[ctrl]a[ctrl-up][delete]');
	F("#editStockLimit").type(VALID_PRODUCTSTOCKLIMIT);
	F("#editProductImage").click(VALID_IMAGE);
	F("#addTags1").type('[ctrl]a[ctrl-up][delete]');
	F("#addTags1").type(VALID_TAG1);
	F("#addTags2").type(VALID_TAG2);
	F("#addTags3").type(VALID_TAG3);
	F("#addTags4").type(VALID_TAG4);

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
	F("#editProductName").type('[ctrl]a[ctrl-up][delete]');
	F("#editProductName").type(INVALID_PRODUCTNAME);
	F("#editProductPrice").type('[ctrl]a[ctrl-up][delete]');
	F("#editProductPrice").type(INVALID_PRODUCTPRICE);
	F("#editProductDescription").type('[ctrl]a[ctrl-up][delete]');
	F("#editProductDescription").type(INVALID_PRODUCTDESCRIPTION);
	F("#editProductPriceType").click(INVALID_PRODUCTPRICETYPE);
	F("#editProductWeight").type('[ctrl]a[ctrl-up][delete]');
	F("#editProductWeight").type(INVALID_PRODUCTWEIGHT);
	F("#editStockLimit").type('[ctrl]a[ctrl-up][delete]');
	F("#editStockLimit").type(INVALID_PRODUCTSTOCKLIMIT);

	F("#editSubmit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Exception: product price is not a valid float") === 0, "unsuccessful message");
	});

	F("#editProductPrice").type('[ctrl]a[ctrl-up][delete]');
	F("#editProductPrice").type(VALID_PRODUCTPRICE);
	F("#editProductName").type('[ctrl]a[ctrl-up][delete]');
	F("#editProductName").type(VALID_PRODUCTNAME);
	F("#editProductDescription").type('[ctrl]a[ctrl-up][delete]');
	F("#editProductDescription").type(VALID_PRODUCTDESCRIPTION);
	F("#editProductPriceType").click(VALID_PRODUCTPRICETYPE);
	F("#editProductWeight").type('[ctrl]a[ctrl-up][delete]');
	F("#editProductWeight").type(VALID_PRODUCTWEIGHT);
	F("#editStockLimit").type('[ctrl]a[ctrl-up][delete]');
	F("#editStockLimit").type(VALID_PRODUCTSTOCKLIMIT);
	F("#editProductImage").click(INVALID_IMAGE);

	F.wait(10000);

	F("#editSubmit").click();

	F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Exception: The input image file should be either jpg, JPG, jpeg, or JPEG") === 0, "unsuccessful message");
	});


}

// the test function *MUST* be called in order for the test to execute
test("test valid fields", testValidFields);
test("test invalid fields", testInvalidFields);