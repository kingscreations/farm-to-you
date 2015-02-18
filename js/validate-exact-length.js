/**
 * add exactlength to check the exact length of a field
 */
$.validator.addMethod("exactlength",
	function(value, element, param) {
		return this.optional(element) || value.length == param;
	},
	$.validator.format("Please enter exactly {0} characters.")
);