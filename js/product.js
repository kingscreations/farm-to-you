$(document).ready(function() {

	// caching jQuery
	var $productController = $('#product-controller');

	$productController.on('submit', function(event) {

		event.preventDefault();

		var $product         = $('[name=product]');
		var $productQuantity = $('[name=productQuantity]');


		var productQuantity = $productQuantity.val();
		if(productQuantity === null) {
			return;
		}

		var data = {
			product        : $product.val(),
			productQuantity: productQuantity
		}

		//console.log($product.val());
		//console.log(productQuantity);

		$.ajax({
			type: "POST",
			url: "../php/forms/product-controller.php",
			dataType: 'json',
			data: data
		})
			.done(function(jsonOutput) {
console.log(jsonOutput);
				// if there is an error
				if(typeof(jsonOutput.error) !== 'undefined') {
					console.error(jsonOutput.error);
					return;
				}

				$("#outputArea").css("display", "");
				$("#outputArea").html(jsonOutput.message);

				// update the cart icon count
				var $cartCount = $('#cart-main-menu-item a .count');
				if($cartCount.length > 0) {
					$cartCount.text(jsonOutput.cartCount);
				} else {
					$('#cart-main-menu-item a').append('<span class="count">' + jsonOutput.cartCount + '</span>');
				}

				// get the select html dropdown menu tag
				var $productQuantitySelect = $('#product-quantity');

				// remove all the options from the select html parent tag
				$productQuantitySelect.children().each(function(index, selectOption) {
					$(this).remove();
				});

				if(jsonOutput.availableQuantity === 0) {
					console.log('debug');
					$productQuantitySelect.append('<option selected="selected">0</option>');
					$productQuantitySelect.disable();
				}

				// recreate all the options in the same select html parent tag
				for(var i = 0; i < jsonOutput.availableQuantity; i++) {
					if(i === 0) {
						$productQuantitySelect.append('<option selected="selected">' + (i + 1) + '</option>');
					} else {
						$productQuantitySelect.append('<option>' + (i + 1) + '</option>');
					}
				}

				// finally update the number of product available (just underneath the price)
				var message = '';
				if(jsonOutput.availableQuantity < 15 && jsonOutput.availableQuantity !== 0) {
					message = 'Only ' + jsonOutput.availableQuantity + ' available';
				} else if(jsonOutput.availableQuantity === 0) {
					message = jsonOutput.availableQuantity + ' available';
				} else if(jsonOutput.availableQuantity > 60) {
					message = 'A lot available';
				} else {
					message = jsonOutput.availableQuantity +' available';
				}
				$('#available-quantity').text(message);

				if($(".alert-success").length >= 1) {
					$productController[0].reset();
				}

			});

	});

});