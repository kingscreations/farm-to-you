
/**
 * add exactlength to check the exact length of a field
 */
$.validator.addMethod("exactlength",
	function(value, element, param) {
		return this.optional(element) || value.length == param;
	},
	$.validator.format("Please enter exactly {0} characters.")
);

$.validator.addMethod("notEqualTo",
	function(value, element) {
		var $element = $(element);
		if($element.val() === "") {
			return true;
		}
		var $parentDesktop = $('.input-tags.hidden-xs');
		if($parentDesktop.css("display") === 'none') {

			// get only mobile elements
			var $tags = $('.visible-xs .distinctTags').not($element);
		} else {

			// get only desktop elements
			var $tags = $('.hidden-xs .distinctTags').not($element);
		}
		var tagsArray = $.map($tags, function(distinctTags) {
		return $(distinctTags).val();
	});
	if($.inArray($element.val(), tagsArray) >= 0) {
		return false;
	} else {
		return true;
	}
}, "Duplicate tag");
