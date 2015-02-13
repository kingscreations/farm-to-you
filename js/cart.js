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

	 $('.product-quantity').on('change', function() {

		 if($(this) === null || $(this).length === 0) {
			 return;
		 }

		 var elementId = $(this)[0].id;

		 // get the first part of the id: product#
		 var elementIdPart1 = elementId.split('-')[0];

		 var $inputPrice = $('#'+ elementIdPart1 +'-price');
		 var $inputWeight = $('#'+ elementIdPart1 +'-weight');

		 // get the price and the product price type
		 var productPrice = null;
		 if($inputPrice.val().indexOf('/lb') >= 0) {
			 var productPriceType = 'w';
			 productPrice = $inputPrice.split('/lb');
		 } else {
			 var productPriceType = 'u';
			 productPrice = $inputPrice.val();
		 };

		 var productWeight = $inputWeight.val();

		var data = {
			'newQuantity': $(this).val(),
			'productPrice': productPrice,
			'productPriceType': productPriceType,
			'productWeight': productWeight
		}
		$.ajax({
			 type: 'post',
			 url: '../php/forms/cart-controller.php',
			 data: data
		}).done(function(ajaxOutput) {
			 console.log('success!');
			$('#'+ elementIdPart1 +'-total-price').html(ajaxOutput);
		});
	 })
});