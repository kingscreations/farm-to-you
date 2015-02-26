$(document).ready(function() {

	/**
	 * form validation and first call to stripe with the createToken function
	 */
	$("#product-controller").validate({
		errorClass: "label-danger",
		errorLabelContainer: "#outputArea",
		wrapper: "li",

		rules: {
			productWeight: {
				number: true,
				required: true
			},
			productQuantity: {

			}
		},
		messages: {
			productWeight: {
				number: "The product weight must be a number.",
				required: "Please enter the weight of the product."
			},
			productQuantity: {

			}
		},

		submitHandler: function(form) {
			var $form = $(form);

			// Disable the submit button to prevent repeated clicks
			$form.find('button')
				.prop('disabled', true)
				.addClass('disabled');

			var $product= $('[name=product]');
			var $productWeight = $('[name=productWeight]');
			var $productQuantity = $('[name=productQuantity]');

			var data = {
				product: $product.val()
			}

			if($productQuantity.length !== 0) {
				data.productQuantity = $productQuantity.val();
			}

			if($productWeight.length !== 0) {
				data.productWeight = $productWeight.val();
			}
			console.log(data);

			$form.ajaxSubmit({
				type: "POST",
				url: "../php/forms/product-controller.php",
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
});