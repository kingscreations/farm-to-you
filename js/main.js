/**
 * main javascript file
 *
 * contains all the basic javascript routine
 *
 * @author Florian Goussin <florian.goussin@gmail.com>
 */

(function() {

	$(document).ready(function() {

		var myScroll;

		function loaded () {
			myScroll = new IScroll('#wrapper', { scrollX: true, scrollY: false, mouseWheel: true });
		}

		document.addEventListener('touchmove', function (e) {
			e.preventDefault();
		}, false);

	});

})() // sandbox all the inside code to prevent global variable creation