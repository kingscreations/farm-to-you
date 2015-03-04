// document ready event
$(document).ready(
	// inner function for the ready() event
	function() {

		// tell the validator to validate this form (by id)
		$("#search").validate({
			// setup the formatting for the errors
			errorClass: "label-danger",
			errorLabelContainer: "#outputArea",
			wrapper: "li",

			// rules define what is good/bad input
			rules: {
				// each rule starts with the inputs name (NOT id)
				inputSearch: {
					minlength: 1,
					required: true
				}
			},

			// error messages to display to the end user
			messages: {
				inputFirstname: {

					minlength: "Search must have at least 1 character",
					required: "Please enter a search term"
				}
			}
		});

		$productsCardId = $('[id^=product-]');

		$('.list-group-item').on('click', function() {

			// the default link should not trigger the ajax callback
			if($(this).hasClass('static')) {
				$productsCardId.each(function() {
					$(this).show();
				});
				return;
			}

			var data = {
				'category': $(this).text(),
				'searchTerm': $('#search-term').text()
			}

			$.ajax({
				type: "post",
				url: "../php/forms/category-search-controller.php",
				data: data
			})
				.done(function(jsonOutput) {
					var jsonOutput = jQuery.parseJSON(jsonOutput);

					// if there is an error
					if(typeof(jsonOutput.error) !== 'undefined') {
						console.log(jsonOutput.error);
						return;
					}
					var productIds = jsonOutput.products;

					// loop for each product card from the store view
					$productsCardId.each(function() {
						var $current = $(this);
						var hide = false;

						for(var i = 0; i < productIds.length; i++) {

							// get the id integer from the html id property
							var id = parseInt($current.prop('id').split('product-')[1]);
							if(id === productIds[i]) {
								hide = true;
							}
						}

						if(hide === true) {
							$current.hide();
						} else {
							$current.show();
						}
					});
				});
		})
	});

			// setup an AJAX call to submit the form without reloading
			//submitHandler: function(form) {
			//	$(form).ajaxSubmit({
			//		// GET or POST
			//		type: "POST",
			//		// where to submit data
			//		url: "../php/forms/add-profile-controller.php",
			//		// TL; DR: reformat POST data
			//		data: $(form),
			//		// success is an event that happens when the server replies
			//		success: function(ajaxOutput) {
			//			// clear the output area's formatting
			//			$("#outputArea").css("display", "block");
			//			// write the server's reply to the output area
			//			$("#outputArea").html(ajaxOutput);
			//
			//
			//			// reset the form if it was successful
			//			// this makes it easier to reuse the form again
			//			if($(".alert-success").length >= 1) {
			//				$(form)[0].reset();
			//			}
			//		}
			//	});
			//}

