/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

$(document).ready(function() {

	/**
	 * click on the "Continue to checkout" button
	 */
	$('#checkoutShippingController').on('submit', function() {

		// the value to put in the data object
		var storeLocations = [];

		$('[id^=store-]').each(function() {

			var $currentStore = $(this);

			// we don't want the store location to be selected
			if($currentStore.prop('id').indexOf("_") !== -1) {
				return;
			}

			var store = $(this).prop('id').split('store-')[1];

			var location = null;

			$('[id^=location-]').each(function() {

				var $current = $(this);

				// if the menu item has the active class, then select this pickup location
				if($current.hasClass('active') === true) {

					// get the location id
					location = $current.prop('id').split('location-')[1];
					return;
				}
			});

			if(location !== null) {
				// store everything in a home made map
				var $storeLocation = $('#store-'+ store + '_location-' + location);

				$storeLocation.val(store + '|' + location);

				// add the name only for the inputs to post
				$storeLocation.prop('name', 'storeLocation[]');
			}
		});

		// submit the form
		this.submit();

	});

	/**
	 * Prevent the default behavior of the link
	 */
	$('.list-group-item').on('click', function(event) {
		event.preventDefault();
	});

});