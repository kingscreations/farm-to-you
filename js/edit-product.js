// MAY NEED TO ADJUST THE VALUES TO NOT BE REQUIRED SINCE THIS IS JUST TO UPDATE. WILL HAVE TO TEST WITH CONTROLLER AS WELL
// document ready event
$(document).ready(
	// inner function for the ready() event
	function() {

		//Back button

		document.getElementById("back").onclick = function () {
			location.href = "../edit-store/index.php";
		};


		// tell the validator to validate this form (by id)
		$("#editProduct").validate({
			// setup the formatting for the errors
			errorClass: "label-danger",
			errorLabelContainer: "#outputArea",
			wrapper: "li",

			// rules define what is good/bad input
			rules: {
				// each rule starts with the inputs name (NOT id)
				editProductName: {

					minlength: 2,
					required: true
				},

				editProductPrice: {

					required: true
				},

				editProductDescription: {

					maxlength: 4294967295,
					required: true
				},

				editProductPriceType: {

					required: true
				},

				editProductWeight: {

					required: true
				},

				editStockLimit: {

					required: true
				},

				editProductImage: {

					maxlength: 255,
					required: false
				}
			},

			// error messages to display to the end user
			messages: {
				editProductName: {

					minlength: "Product Name must be at least 2 characters",
					required: "Please enter a product name."
				},

				editProductPrice: {

					required: "Please enter a product price."
				},

				editProductDescription: {
					maxlength: "Product Description too long!",
					required: "Please write a description of the product."
				},

				editProductPriceType: {

					required: "Please enter a product price type."
				},

				editProductWeight: {
					required: "Please enter your products weight."
				},

				editStockLimit: {
					required: "Please enter your current product stock amount available to sell"
				},

				editProductImage: {

					maxlength: "Image directory is too long!"

				}
			},

			//setup an AJAX call to submit the form without reloading
			submitHandler: function(form) {
				$(form).ajaxSubmit({
					// GET or POST
					type: "POST",
					// where to submit data
					url: "../php/forms/edit-product-controller.php",
					// TL; DR: reformat POST data
					data: $(form),
					// success is an event that happens when the server replies
					success: function(ajaxOutput) {
						// clear the output area's formatting
						$("#outputArea").css("display", "block");
						// write the server's reply to the output area
						$("#outputArea").html(ajaxOutput);

					}
				});
			}
		});
	});