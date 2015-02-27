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
				success: function(cartCount) {
					var productName = $("h1").text();
					$("#outputArea").css("display", "");
					$("#outputArea").html('<p class="alert alert-success">' + productName + ' has been added to the cart!</p>');

					// update the cart icon count
					$('#cart-main-menu-item a').append('<span class="count">' + cartCount + '</span>');

					if($(".alert-success").length >= 1) {
						$(form)[0].reset();
					}
				}
			});
		}
	});
});