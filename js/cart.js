/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

 $(document).ready(function() {
	 var maxQuantity = $("#product1Quantity").children().length;

	 $('#cartController').validate({
		errorClass: "label-danger",
		errorLabelContainer: "#outputArea",
		wrapper: "li",

		rules: {}, // no js validation rules to apply to the select/option input
		messages: {},

		submitHandler: function(form) {
			$(form).ajaxSubmit({
				type: "POST",
				url: "../php/forms/cart-controller.php",
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

	 var $productQuantity = $('.product-quantity');

	 // select quantity on change ajax call to update the total price of each row
	 $productQuantity.on('change', function() {
		 refreshFinalPrice(null, $(this));
		 refreshTotalPrice();
	 });

	 // call the refreshTotalPrice when the page load for the first time
	 //$productQuantity.change();

	 // call the refreshTotalPrice when the page load for the first time for each product
	 $.each($productQuantity, function() {
	 	refreshFinalPrice(null, $(this));
	 });
	 refreshTotalPrice();

	 /**
	  * refresh the final price of a product
	  */
	 function refreshFinalPrice(event, $current) {
		 // set a default value
		 $productQuantity = typeof $current === 'undefined' ? $(this) : $current;

		 // first step to be able to get the other cell of this product row
		 var elementId = $productQuantity[0].id;

		 // get the first part of the id: product# which gives the product number (the row)
		 var elementIdPart1 = elementId.split('-')[0];

		 var $inputPrice = $('#'+ elementIdPart1 +'-price');
		 var $inputWeight = $('#'+ elementIdPart1 +'-weight');

		 // get the product weight and the new quantity
		 var productWeight = parseFloat($inputWeight.text());
		 var newQuantity = parseFloat($productQuantity.val());
		 var productPrice = $inputPrice.text();

		 // set the total price according to the productPriceType
		 if($inputPrice.text().indexOf('lb') >= 0) {

			 // get rid of the /lb AND the $ (first letter)
			 productPrice = parseFloat(productPrice.split('/lb')[0].substring(1));

			 // price per pound
			 var result = productPrice * newQuantity * productWeight;

			 // multiply by 100, round and then divide by 100 to get 2 decimal precision
			 var finalPrice = String((Math.round(result * 100) / 100), 2);

			 $('#'+ elementIdPart1 +'-final-price').html('$'+finalPrice);
		 } else {

			 // get just rid of the $ (first letter)
			 productPrice = parseFloat(productPrice.substring(1));

			 // unit price
			 var result = productPrice * newQuantity;

			 // multiply by 100, round and then divide by 100 to get 2 decimal precision
			 var finalPrice = String((Math.round(result * 100) / 100), 2);

			 $('#'+ elementIdPart1 +'-final-price').html('$'+finalPrice);
		 };
	 }

	 /**
	  * refresh the total price for all the products listed in the cart
	  */
	 function refreshTotalPrice() {
		// get all the elements with id finishing by -final-price
		var $finalPrices = $('table.table tbody tr [id$=-final-price]');

		var totalPrice = 0.0;
		$.each($finalPrices, function() {
			 // get rid of the $ (first letter)
			 var price = parseFloat($(this).text().substring(1));
			 totalPrice += price;
		});

		// show the result
		$('#total-price-result').text(String(totalPrice));
	 }
});