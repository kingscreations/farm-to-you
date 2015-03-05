/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

$(document).ready(function() {

	/**
	 * click on the "Continue to checkout" button
	 */
	$('#checkout-pickup-submit').on('click', function() {

		// the value to put in the data object
		var storeLocations = [];

		$('[id^=store-]').each(function() {
			var store = $(this).prop('id').split('store-')[1];
			var location = null;

			$('[id^=location-]').each(function() {

				var $currrent = $(this);

				// if the menu item has the active class, then select this pickup location
				if($currrent.hasClass('active')) {

					// get the location id
					location = $current.prop('id').split('location-')[1];
					return;
				} else {
					location = null;
				}
			});

			if(location !== null) {
				// store everything in a home made map
				$('store-'+ store + '_location-' + location).val(store + '|' + location);
			}
		});

		this.submit();

	});

});