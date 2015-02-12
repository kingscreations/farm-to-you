/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

 $(document).ready(function() {
	 var maxQuantity = $("#product1Quantity").children().length;

	 $("#cartController").validate({
		errorClass: "label-danger",
		errorLabelContainer: "#outputArea",
		wrapper: "li",

		rules: {
			productQuantity: {
				min: 1,
				max: maxQuantity
			}
		},

		// error messages to display to the end user
		messages: {
			profileId: {
				min: "Product quantity must be positive",
				min: "Product quantity must be less than "+ maxQuantity
			}
		},

		submitHandler: function(form) {
			$(form).ajaxSubmit({
				type: "POST",
				url: "../php/forms/cart-controller.php",
				data: $(form).serialize(),
				success: function(ajaxOutput) {
					console.log(ajaxOutput);
					// clear the output area's formatting
					$("#outputArea").css("display", "block");
					$("#outputArea").html(ajaxOutput);

					if($(".alert-success").length >= 1) {
						$(form)[0].reset();
					}
				}
			});
		}
	});
});