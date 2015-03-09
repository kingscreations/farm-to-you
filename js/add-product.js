/**
 * @author Jay Renteria <jay@jayrenteria.com>
 */


// tell the validator to validate this form (by id)
$("#addProduct").validate({
	// setup the formatting for the errors
	errorClass: "alert-danger",
	errorLabelContainer: "#outputArea",
	wrapper: "li",

	// rules define what is good/bad input
	rules: {
		// each rule starts with the inputs name (NOT id)
		inputProductName: {

			minlength: 2,
			required: true
		},

		inputProductPrice: {

			required: true
		},

		inputProductDescription: {

			maxlength: 4294967295,
			required: true
		},

		inputProductPriceType: {

			required: true
		},

		inputProductWeight: {

			required: false
		},

		inputStockLimit: {

			required: true
		},

		inputImage: {

			maxlength: 255,
			required: false
		},

		addTags1: {
			maxlength: 20,
			notEqualTo: ".distinctTags",
			required: false
		},

		addTags2: {
			maxlength: 20,
			notEqualTo: ".distinctTags",
			required: false
		},

		addTags3: {
			maxlength: 20,
			notEqualTo: ".distinctTags",
			required: false
		},

		addTags4: {
			maxlength: 20,
			notEqualTo: ".distinctTags",
			required: false
		}

	},

	// error messages to display to the end user
	messages: {
		inputProductName: {

			minlength: "Product Name must be at least 2 characters",
			required: "Please enter your first name."
		},

		inputProductPrice: {

			required: "Please enter a product price."
		},

		inputProductDescription: {
			maxlength: "Product Description too long!",
			required: "Please write a description of the product."
		},

		inputProductPriceType: {

			required: "Please enter a product price type."
		},

		inputStockLimit: {
			required: "Please enter your current product stock amount available to sell"
		},

		inputProductImage: {
			maxlength: "Image directory is too long!"
		},

		addTags1: {
			maxlength: "Tag is too long!"
		},

		addTags2: {
			maxlength: "Tag is too long!"
		},

		addTags3: {
			maxlength: "Tag is too long!"
		},

		addTags4: {
			maxlength: "Tag is too long!"
		}

	},

	//setup an AJAX call to submit the form without reloading
	submitHandler: function(form) {
		$(form).ajaxSubmit({
			// GET or POST
			type: "POST",
			// where to submit data
			url: "../php/forms/add-product-controller.php",
			// TL; DR: reformat POST data
			data: $(form),
			// success is an event that happens when the server replies
			success: function(ajaxOutput) {
				// clear the output area's formatting
				$("#outputArea").css("display", "block");
				// write the server's reply to the output area
				$("#outputArea").html(ajaxOutput);

				if(typeof ajaxOutput === 'string') {
					var regex = /\(id\ = ([0-9]+)\) posted!/i;
					var match = regex.exec(ajaxOutput);
					var productId = match[1];
					$('#dynamic-product-list').append('<li><a class="product-item" href="#" id="' + productId + '"></a></li>');
				} else {
					console.error('Exception: Cannot add the product to the #dynamic-product-list');
				}

				// reset the form if it was successful
				// this makes it easier to reuse the form again
				if($(".alert-success").length >= 1) {
					$(form)[0].reset();
				}
			}
		});
	}
});

$('.product-item').click(function() {
	var productId = $(this).attr("id");
	$.ajax({
		type: "POST",
		url: "../php/forms/edit-product-add-to-session.php",
		data: {productId: productId}
	}).done(function() {
		location.href = "../edit-product/index.php";
	});
});