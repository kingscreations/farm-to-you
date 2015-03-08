/**
 * main javascript file
 *
 * contains all the basic javascript routine
 *
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

(function() {

	$(document).ready(function() {

		$('.list-group-item').on('click', function(event) {
			if($(this).hasClass('disabled') === false) {
				$('.list-group-item').removeClass('active');
				$(this).addClass('active');
			}
		});

		$(window).resize(function() {
			var viewportWidth = $(window).width();

			if(viewportWidth < 768) {
				$('#inputSearch').prop('placeholder', 'Search...');
			} else {
				$('#inputSearch').prop('placeholder', 'What are you looking for today?');
			}
		});

		// resize at least the window one time
		$(window).resize();

	});

})(); // sandbox all the inside code to prevent global variable creation