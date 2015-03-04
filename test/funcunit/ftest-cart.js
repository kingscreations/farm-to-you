// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../cart/");
	}
});

var VALID_QUANTITY      = "12";

// thanks dylan!!
var $cartQuantities = $('[id$=-quantity]');

/**
 * test filling in only valid form data
 **/
function testValidFields() {
	// fill in the form values\
	$cartQuantities.val(12);

	// click the button once all the fields are filled in
	F("#cart-validate-button").click();

	F.wait(2000, function(){
		ok(F('h2').text() === 'You don\'t have any choice for the pickup location even if you are supposed to',
			'on the pickup location page')
	})
}

// start the test
test("test valid fields", testValidFields);