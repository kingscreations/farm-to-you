/**
 * Created by jason on 2/12/2015.
 */

// document ready event
$(document).ready(
// inner function for the ready() event
	function() {
// tell the validator to validate this form
		$("#signUp").validate({
// setup the formatting for the errors
			errorClass: "label-danger",
			errorLabelContainer: "#outputArea",
			wrapper: "li",
// rules define what is good/bad input
			rules: {
// each rule starts with the inputs name (NOT id)
				inputEmail: {
					minlength: 6,
					maxlength: 100,
					required: true,
					email: true
				},
				password1: {
					maxlength: 200,
					required: true
				},
				passwordCheck: {
					equalTo: "#password1",
					maxlength: 200,
					required: true
				}
			},
			// error messages to display to the end user
			messages: {
				inputEmail: {
					minlength: "Email is too short",
					maxlength: "Email is too long",
					required: "Please enter an email address",
					email: "Please enter a valid email address"
				},
				password1: {
					maxlength: "Password is too long.",
					required: "please enter a password."
				},
				passwordCheck: {
					equalTo: "Passwords do not match",
					maxlength: "Password is too long.",
					required: "please enter a password."
				}
			},
			//establish that both passwords match

			// setup an AJAX call to submit the form without reloading
			submitHandler: function(form) {
				$(form).ajaxSubmit({
					// GET or POST
					type: "POST",
					// where to submit data
					url: "../php/forms/sign-up-controller.php",
					// TL; DR: reformat POST data
					data: $(form),
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


