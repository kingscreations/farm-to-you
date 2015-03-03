// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../product/index.php?product=1");
	}
});

var $productQuantity = $('');

var VALID_QUANTITY = 5;

var INVALID_QUANTITY = 55674654764657;

/**
 * test filling in only valid form data
 **/
function testValidFields() {

	// fill in the cart
	$productQuantity.val(VALID_QUANTITY);

	// click the button once all the fields are filled in
	F("#add-product-to-cart").click();

	// add four more products (quantity has been normally set automatically to 1 after the first click)
	F("#add-product-to-cart").click();
	F("#add-product-to-cart").click();

	F.wait(500, function(){
		F(".alert").visible(function() {
			// create a regular expression that evaluates the successful text
			var successRegex = /^[0-9]+ \w+ ha(s|ve) been added to your cart./;

			// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
			ok(F(this).hasClass("alert-success"), "successful alert CSS");
		});
	});
}

///**
// * test filling in invalid form data
// **/
function testInvalidFields() {

}

test("test valid add product to the cart", testValidFields);