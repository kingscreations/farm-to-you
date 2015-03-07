/**
 * Created by jason on 2/19/2015.
 */
// document ready event
$(document).ready(
// inner function for the ready() event
	function() {
// tell the validator to validate this form
		$("#signIn").validate({
// setup the formatting for the errors
			errorClass: "label-danger",
			errorLabelContainer: "#outputArea",
			wrapper: "li",
// rules define what is good/bad input
			rules: {
// each rule starts with the inputs name (NOT id)
				email: {
					maxlength: 140,
					required: true,
					email: true
				},
				password2: {
					maxlength: 200,
					required: true
				}
			},
			// error messages to display to the end user
			messages: {
				email: {
					min: "Email is too long",
					required: "Please enter an email address",
					email: "Please enter a valid email address"
				},
				password2: {
					maxlength: "Password is too long.",
					required: "please enter a password."
				}
			},
			// THIS WAS CAUSING THE INDEX TO DISPLAY IN THE WINDOW OF THE SIGNUP, FIXED BY TAKING THIS OUT.
			//setup an AJAX call to submit the form without reloading
			submitHandler: function(form) {
				$(form).ajaxSubmit({
					// GET or POST
					type: "POST",
					// where to submit data
					url: "../php/forms/sign-in-controller.php",
					// TL; DR: reformat POST data
					data: $(form),
					// success is an event that happens when the server replies
					success: function(ajaxOutput) {
						// clear the output area's formatting
						$("#outputArea").css("display", "");
						// write the server's reply to the output area
						$("#outputArea").html(ajaxOutput);

						if(ajaxOutput.indexOf("alert-success") !== -1){
							setTimeout(function(){
								window.location.href = "../new-user/index.php";
							}, 1000);
						}

						if(ajaxOutput.indexOf("alert-danger") !== -1) {
							setTimeout(function(){
								window.location.href = "../sign-in/index.php"
							}, 500);
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
	});