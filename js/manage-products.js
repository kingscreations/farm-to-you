/**
 * @author Jay Renteria <jay@jayrenteria.com>
 *
 * @author Florian Goussin <florian.goussin@gmail.com>
 *    edit: take the work of jay (add-products.js) and copy it in here to separate the concerns for the end-user
 */


$('.editButton').click(function() {
	var productId = $(this).attr("id");
	$.ajax({
		type: "POST",
		url: "../php/forms/edit-product-add-to-session.php",
		data: {productId: productId}
	}).done(function() {
		location.href = "../edit-product/index.php";
	});
});

$('.deleteProductButton').click(function() {
	var productId = $(this).attr("id");
	$.ajax({
		type: "POST",
		url: "../php/forms/delete-product-add-to-session.php",
		data: {productId: productId}
	}).done(function() {
		location.href = "../add-product/index.php";
	});
});

//Back button
document.getElementById("back").onclick = function () {
	location.href = "../edit-store/index.php";
};
