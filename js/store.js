$(document).ready(function() {

	/**
	 *
	 * http://stackoverflow.com/questions/11582512/how-to-get-url-parameters-with-javascript/11582513#11582513
	 *
	 *
	 * @param name the name of the url parameter
	 * @returns {string|null}
	 */
	function getURLParameter(name) {
		var regex = new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)');
		return decodeURIComponent((regex.exec(location.search) || [,""])[1].replace(/\+/g, '%20'))||null
	}

	$productsCardId = $('[id^=product-]');

	$('.list-group-item').on('click', function(event) {

		// list-group-item is a link
		event.preventDefault();

		// the default link should not trigger the ajax callback
		if($(this).hasClass('static')) {
			$productsCardId.each(function() {
				$(this).show();
			});
			return;
		}

		var data = {
			'category': $(this).text(),
			'store': getURLParameter('store')
		}

		$.ajax({
			type: "post",
			url: "../php/forms/store-controller.php",
			dataType: 'json',
			data: data
		})
			.done(function(jsonOutput) {

				// if there is an error
				if(typeof(jsonOutput.error) !== 'undefined') {
					console.error(jsonOutput.error);
					return;
				}
				var productIds = jsonOutput.products;

				// loop for each product card from the store view
				$productsCardId.each(function() {
					var $current = $(this);
					var hide     = false;

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