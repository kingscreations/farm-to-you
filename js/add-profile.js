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
					min: 1,
					required: true
				},
				tweetContent: {
					maxlength: 140,
					required: true
				}
			},

			// error messages to display to the end user
			messages: {
				profileId: {
					min: "Profile id must be positive",
					required: "Please enter a profile id"
				},
				tweetContent: {
					maxlength: "Tweet is too long.",
					required: "What's on your mind?"
				}
			},

			// setup an AJAX call to submit the form without reloading
			submitHandler: function(form) {
				$(form).ajaxSubmit({
					// GET or POST
					type: "POST",
					// where to submit data
					url: "../../week3/mvc/controller-post.php",
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