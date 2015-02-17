/**
 * @author Florian Goussin <florian.goussin@gmail.com>
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

	$("button").click(
		function() {
			$("#outputArea").load('../php/forms/store-controller.php');
		}
	);
});