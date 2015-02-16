/**
 * Created by jason on 2/12/2015.
 */

// document ready event
$(document).ready(
	// inner function for the ready() event
	function() {
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
			alert("Thank you.. Please open the email we just sent to you and click the link within to finish your registration.")

		});
	});


