/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

$(document).ready(function() {
	var maxQuantity = $("#product1Quantity").children().length;

	$('#checkoutShippingController').validate({
		errorClass: "label-danger",
		errorLabelContainer: "#outputArea",
		wrapper: "li",

		rules: {}, // no js validation rules to apply to the select/option input
		messages: {},

		submitHandler: function(form) {
			$(form).ajaxSubmit({
				type: "POST",
				url: "../php/forms/checkout-shipping-controller.php",
				data: $(form),
				success: function(ajaxOutput) {
					$("#outputArea").css("display", "block");
					$("#outputArea").html(ajaxOutput);

					if($(".alert-success").length >= 1) {
						$(form)[0].reset();
					}
					window.location.href = "https://bootcamp-coders.cnm.edu/~fgoussin/farm-to-you/checkout/";
				}
			});
		}
	});

});