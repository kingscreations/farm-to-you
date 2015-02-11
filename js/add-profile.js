// document ready event
$(document).ready(
	// inner function for the ready() event
	function() {

		// tell the validator to validate this form
		$("#addprofle").validate({
			// setup the formatting for the errors
			errorClass: "label-danger",
			errorLabelContainer: "#outputArea",
			wrapper: "li",

			// rules define what is good/bad input
			rules: {
				// each rule starts with the inputs name (NOT id)
				inputFirstname: {

					maxlength: 45,
					required: true
				},

				inputLastname: {

					maxlength: 45,
					required: true
				},

				inputType: {

					required: true
				},

				inputPhone: {

					maxlength: 20,
					required: true
				},

				inputImage: {

					maxlength:100,
					required: false
				}
			},

			// error messages to display to the end user
			messages: {
				inputFirstname: {

					maxlength: "First Name is too long!",
					required: "Please enter your first name."
				},

				inputLastname: {
					maxlength: "Last Name is too long!",
					required: "Please enter your last name."
				},

				inputType: {
					required: "Please select account type."
				},

				inputPhone: {
					maxlength: "Phone number is too long!",
					required: "Please enter a phone number."
				},

				inputImage: {

					maxlength: "Image directory is too long!"

				}
			},

			// setup an AJAX call to submit the form without reloading
			submitHandler: function(form) {
				$(form).ajaxSubmit({
					// GET or POST
					type: "POST",
					// where to submit data
					url: "../php/forms/add-profile-controller.php",
					// TL; DR: reformat POST data
					data: $(form).serialize(),
					// success is an event that happens when the server replies
					success: function(ajaxOutput) {
						// clear the output area's formatting
						$("#outputArea").css("display", "");
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