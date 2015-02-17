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
				// ensure email value has been entered
				if(emailVal == '') {
					$("#inputEmail").after('<span class="error">Please enter your email.</span>');
				}
				// ensure first password is entered
				if(passwordVal == '') {
					$("#password").after('<span class="error">Please enter a password.</span>');
					hasError = true;
				} else if(checkVal == '') {// ensure second password is entered
					$("#password-check").after('<span class="error">Please re-enter your password.</span>');
					hasError = true;
				} else if(passwordVal !== checkVal) {// make sure passwords match
					$("#password-check").after('<span class="error">Passwords do not match.</span>');
					hasError = true;
				}
				if(hasError == true) {
					return false;
				}
			});
			// after form is complete and passwords match. alert user
			alert("Thank you.. Please open the email we just sent to you and click the link within to finish your registration.")

		});
	});


