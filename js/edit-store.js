/**
 * @author Alonso Indacochea <alonso@hermesdevelopment.com>
 */

$(document).ready(
// inner function for the ready() event

	function() {

		$('.editButton').click(function() {
			var locationId = $(this).attr("id");
			$.ajax({
				type: "POST",
				url: "../php/forms/edit-location-add-to-session.php",
				data: {locationId: locationId}
			}).done(function() {
				location.href = "../edit-location/index.php";
			});
		});

		document.getElementById("back").onclick = function () {
			location.href = "../add-store/index.php";
		};


		$('.addButton').click(function() {
			var storeId = $(this).attr("id");
			$.ajax({
				type: "POST",
				url: "../php/forms/add-store-add-to-session.php",
				data: {storeId: storeId}
			}).done(function() {
				location.href = "../add-location/index.php";
			});
		});


		// tell the validator to validate this form (by id)
		$("#editStoreController").validate({
			// setup the formatting for the errors
			errorClass: "label-danger",
			errorLabelContainer: "#outputArea",
			wrapper: "li",

			// rules define what is good/bad input
			rules: {
				// each rule starts with the inputs name (NOT id)
				editStoreName: {
					maxlength: 100,
					required: true
				},

				editStoreDescription: {
					maxlength: 4294967295,
					required: false
				},

				editInputImage: {

					required: false,
					maxlength: 255
				}

			},

			// error messages to display to the end user
			messages: {
				editStoreName: {

					maxlength: "Store name cannot exceed 100 characters",
					required: "Please enter the store name."
				},

				editStoreDescription: {
					maxlength: "Store description too long!"
				},

				editInputImage: {
					maxlength: "Image path is too long!"

				}
			},

			//setup an AJAX call to submit the form without reloading
			submitHandler: function(form) {
				$(form).ajaxSubmit({
					// GET or POST
					type: "POST",
					// where to submit data
					url: "../php/forms/edit-store-controller.php",
					// TL; DR: reformat POST data
					data: $(form),
					// success is an event that happens when the server replies
					success: function(ajaxOutput) {
						// clear the output area's formatting
						$("#outputArea").css("display", "block");
						// write the server's reply to the output area
						$("#outputArea").html(ajaxOutput);
					}
				});
			}
		});
	});