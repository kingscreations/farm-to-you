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

	 /**
	  * select quantity on change ajax call to update the total price of each row
	  *
	  */
	 $('.product-quantity').on('change', function() {

		 if($(this) === null || $(this).length === 0) {
			 return;
		 }

		 // first step to be able to get the other cell of this product row
		 var elementId = $(this)[0].id;

		 // get the first part of the id: product# which gives the product number (the row)
		 var elementIdPart1 = elementId.split('-')[0];

		 var $inputPrice = $('#'+ elementIdPart1 +'-price');
		 var $inputWeight = $('#'+ elementIdPart1 +'-weight');

		 var productWeight = parseFloat($inputWeight.text());
		 var productPrice = null;
		 var newQuantity = parseFloat($(this).val());

		 console.log(productWeight);
		 console.log(newQuantity);

		 // set the total price according to the productPriceType
		 if($inputPrice.text().indexOf('lb') >= 0) {
			 productPrice = parseFloat($inputPrice.text().split('/lb')[0].substring(1));

			 var finalPrice = String(Math.round(productPrice * newQuantity * productWeight), 2);
			 $('#'+ elementIdPart1 +'-total-price').html('$'+finalPrice);
		 } else {
			 productPrice = parseFloat($inputPrice.text()[0].substring(1));

			 var finalPrice = String(Math.round(productPrice * newQuantity), 2);
			 $('#'+ elementIdPart1 +'-total-price').html('$'+finalPrice);
		 };

		 //console.log(productPrice);
		 //console.log($inputWeight);
		//var data = {
		//	'newQuantity': $(this).val(),
		//	'productPrice': productPrice,
		//	'productPriceType': productPriceType,
		//	'productWeight': productWeight
		//}
		//$.ajax({
		//	 type: 'post',
		//	 url: '../php/forms/cart-controller.php',
		//	 data: data
		//}).done(function(ajaxOutput) {
		//	$('#'+ elementIdPart1 +'-total-price').html(ajaxOutput);
		//});
	 })
});