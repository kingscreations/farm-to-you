/**
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

$(document).ready(function() {

	//$('.collapsed').on('click', function() {
	//
	//});

	/**
	 * will only work with js activated
	 * checkout-pickup needs eventually to be old-browser compatible
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
				}
			});

			// format the store location
			var storeLocation = {
				'store': store,
				'location': location
			}

			// finally put the store location in the store locations array
			storeLocations = array_push(storeLocation);
		});


	});

});