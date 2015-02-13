/**
 * Created by jason on 2/12/2015.
 */
// document ready event

jQuery(function() {
	$("#submit").click(function() {
		$(".error").hide();
		var hasError = false;
		var emailVal = $("#inputEmail").val();
		var passwordVal = $("#password").val();
		var checkVal = $("#passwordCheck").val();
		if(emailVal == '') {
			$("#inputEmail").after('<span class="error">Please enter your email.</span>');
		}
		if(passwordVal == '') {
			$("#password").after('<span class="error">Please enter a password.</span>');
			hasError = true;
		} else if(checkVal == '') {
			$("#password-check").after('<span class="error">Please re-enter your password.</span>');
			hasError = true;
		} else if(passwordVal !== checkVal) {
			$("#password-check").after('<span class="error">Passwords do not match.</span>');
			hasError = true;
		}
		if(hasError == true) {
			return false;
		}
	});
	//console.echo("Thank you.. Please open the email we just sent to you and click the link within to finish your registration.")

	});
//$(document).ready(
//	// inner function for the ready() event
//	function() {
//
//		// tell the validator to validate this form
//		$("#addSignUp").validate({
//			// setup the formatting for the errors
//			errorClass: "label-danger",
//			errorLabelContainer: "#outputArea",
//			wrapper: "li",
//
//			// rules define what is good/bad input
//			rules: {
//				// each rule starts with the inputs name (NOT id)
//				inputEmail: {
//
//					maxlength: 100,
//					required: true
//				},
//
//				inputPassword: {
//					required: true
//				},
//
//				inputPasswordCheck: {
//					required: true
//				},
//
//			// error messages to display to the end user
//			messages: {
//				inputEmail: {
//					maxlength: "Email is too long!",
//					required: "Please enter your email address."
//				},
//
//				inputPassword: {
//					required: "Please enter a password."
//				},
//
//				inputPasswordCheck: {
//					required: "Please re-enter your password."
//				},
//			}
//			// verify the two passwords are identical
//			function prepareEventHandlers() {
//				document.getElementById("outputArea").onsubmit = function() {
//					if ("password" !== "passwordCheck"){
//						console.echo("Your passwords don't match. Please try again.")
//					return false;
//					}else("firstPassword" == "secondPassword"){
//						console.echo("Thank you.. Please open the email we just sent to you and click the link within to finish your registration.")
//					return true;
//					}
//		}
//	}
//
//			// setup an AJAX call to submit the form without reloading
//			submitHandler: function(form) {
//				$(form).ajaxSubmit({
//					// GET or POST
//					type: "POST",
//					// where to submit data
//					url: "../php/forms/sign-up-controller.php",
//					// TL; DR: reformat POST data
//					data: $(form).serialize(),
//					// success is an event that happens when the server replies
//					success: function(ajaxOutput) {
//						// clear the output area's formatting
//						$("#outputArea").css("display", "");
//						// write the server's reply to the output area
//						$("#outputArea").html(ajaxOutput);
//
//
//						// reset the form if it was successful
//						// this makes it easier to reuse the form again
//						if($(".alert-success").length >= 1) {
//							$(form)[0].reset();
//						}
//					}
//				});
//			}
//		});
//})

