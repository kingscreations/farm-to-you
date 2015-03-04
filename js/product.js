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

			var $product         = $('[name=product]');
			var $productQuantity = $('[name=productQuantity]');

			var data = {
				product         : $product.val(),
				$productQuantity: $productQuantity.val()
			}

			$form.ajaxSubmit({
				type: "POST",
				url: "../php/forms/product-controller.php",
				data: data,
				success: function(jsonOutput) {

					var jsonOutput = jQuery.parseJSON(jsonOutput);

					$("#outputArea").css("display", "");
					$("#outputArea").html(jsonOutput.message);

					// update the cart icon count
					var $cartCount = $('#cart-main-menu-item a .count');
					if($cartCount.length > 0) {
						$cartCount.text(jsonOutput.cartCount);
					} else {
						$('#cart-main-menu-item a').append('<span class="count">' + jsonOutput.cartCount + '</span>');
					}

					if($(".alert-success").length >= 1) {
						$(form)[0].reset();
					}
				}
			});
		}
	});
});