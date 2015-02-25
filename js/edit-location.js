/**
 * @author Alonso Indacochea <alonso@hermesdevelopment.com>
 */

$(document).ready(

	function() {

		// form validation
		$("#editLocationController").validate({
			// setup the formatting for the errors
			errorClass: "label-danger",
			errorLabelContainer: "#outputArea",
			wrapper: "li",

			rules: {
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

				country: {

					required: false,
					minlength: 2,
					maxlength: 2
				}

			},

			messages: {
				locationName: {

					maxlength: "Location name cannot exceed 100 characters",
					required: "Please enter the location name."
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

				}
			},

			//setup an AJAX call to submit the form without reloading
			submitHandler: function(form) {
				$(form).ajaxSubmit({
					type: "POST",
					url: "../php/forms/edit-location-controller.php",
					data: $(form),
					success: function(ajaxOutput) {
						$("#outputArea").css("display", "block");
						$("#outputArea").html(ajaxOutput);
					}
				});
			}
		});
	});