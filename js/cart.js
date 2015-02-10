/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

 $(document).ready(function() {

		$("#tweetController").validate({
			errorClass: "label-danger",
			errorLabelContainer: "#outputArea",
			wrapper: "li",

			rules: {
				productQuantity: {
					min: 1,
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

			submitHandler: function(form) {
				$(form).ajaxSubmit({
					type: "POST",
					url: "../../week3/mvc/controller-post.php",
					// TL; DR: reformat POST data
					data: $(form).serialize(),
					// success is an event that happens when the server replies
					success: function(ajaxOutput) {
						// clear the output area's formatting
						$("#outputArea").css("display", "");
						// write the server's reply to the output area
						$("#outputArea").html(ajaxOutput);


						// reset if success
						if($(".alert-success").length >= 1) {
							$(form)[0].reset();
						}
					}
				});
			}
		});
});