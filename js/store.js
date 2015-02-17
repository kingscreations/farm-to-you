/**
 * @author Alonso Indacochea <alonso@hermesdevelopment.com>
 */

$(document).ready(function() {

	$('#storeController').validate({
		errorClass: "label-danger",
		errorLabelContainer: "#outputArea",
		wrapper: "li",

		rules: {}, // no js validation rules to apply to the select/option input
		messages: {},

		submitHandler: function(form) {
			$(form).ajaxSubmit({
				type: "POST",
				url: "../php/forms/store-controller.php",
				data: $(form),
				success: function(ajaxOutput) {
					$("#outputArea").css("display", "block");
					$("#outputArea").html(ajaxOutput);

					if($(".alert-success").length >= 1) {
						$(form)[0].reset();
					}
				}
			});
		}
	});

	$("storeButton").click(
		function() {
			$("#outputArea").load('../php/forms/store-controller.php');
		}
	);
	$("locationButton").click(
		function() {
			$("#outputArea").load('../php/forms/location-controller.php');
		}
	);
});