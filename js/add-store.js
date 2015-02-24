/**
* @author Alonso Indacochea <alonso@hermesdevelopment.com>
*/

// document ready event
$(document).ready(function() {
// inner function for the ready() event

	$('.editButton').click(function() {
		var storeId = $(this).attr("id");
		$.ajax({
			type: "POST",
			url: "../php/forms/edit-store-add-to-session.php",
			data: {storeId: storeId}
		}).done(function() {
			location.href = "../edit-store/index.php";
		});
	});

	// tell the validator to validate this form (by id)
	$("#storeController").validate({
		// setup the formatting for the errors
		errorClass: "label-danger",
		errorLabelContainer: "#outputArea",
		wrapper: "li",

		// rules define what is good/bad input
		rules: {
			// each rule starts with the inputs name (NOT id)
			storeName: {
				maxlength: 100,
				required: true
			},

			storeDescription: {
				maxlength: 4294967295,
				required: false
			},

			locationName: {
				maxlength: 100,
				required: true
			},

			address1: {
				maxlength: 150,
				required: true
			},

			address2: {
				maxlength: 150,
				required: false
			},

			zipCode: {
				required: true,
				zipcodeUS: true
			},

			city: {

				required: true,
				maxlength: 100
			},

			state: {

				required: true,
				minlength: 2,
				maxlength: 2
			},

			imagePath: {

				required: false,
				maxlength: 255
			},
			country: {

				required: false,
				minlength: 2,
				maxlength: 2
			}

		},

		// error messages to display to the end user
		messages: {
			storeName: {

				maxlength: "Store name cannot exceed 100 characters",
				required: "Please enter the store name."
			},

			locationName: {

				maxlength: "Location name cannot exceed 100 characters",
				required: "Please enter the location name."
			},

			storeDescription: {
				maxlength: "Store description too long!"
			},

			address1: {
				required: "Please enter a street address.",
				maxlength: "Address line 1 too long"
			},

			address2: {
				maxlength: "Address line 2 too long"
			},

			zipCode: {

				required: "Please enter a zip code.",
				zipcodeUS: "Not a valid zip code."

			},

			city: {

				required: "Please enter a city.",
				maxlength: "City name too long!"

			},

			state: {
				required: "Please enter the state abbreviation.",
				minlength: "State abbreviation must be two characters.",
				maxlength: "State abbreviation must be two characters."

			},

			country: {
				minlength: "Country must be two characters.",
				maxlength: "Country must be two characters."

			},

			imagePath: {
				maxlength: "Image path is too long!"

			}
		},

		//setup an AJAX call to submit the form without reloading
		submitHandler: function(form) {
			$(form).ajaxSubmit({
				// GET or POST
				type: "POST",
				// where to submit data
				url: "../php/forms/add-store-controller.php",
				// TL; DR: reformat POST data
				data: $(form),
				// success is an event that happens when the server replies
				success: function(ajaxOutput) {
					// clear the output area's formatting
					$("#outputArea").css("display", "block");
					// write the server's reply to the output area
					$("#outputArea").html(ajaxOutput);


					// reset the form if it was successful
					// this makes it easier to reuse the form again
					if($(".alert-success").length >= 1) {
						$(form)[0].reset();
					}
				}
			});
		}
	});
});