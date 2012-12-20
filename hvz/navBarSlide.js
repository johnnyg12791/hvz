$("#toggle_menu").bind("click", function() {
	if ($("#navigation").is(":visible") ) {
		$("#toggle_menu").html("Show");
		$("#toggle_menu").css("color", "blue");
	}
	else {
		$("#toggle_menu").html("Hide");
		$("#toggle_menu").css("color", "pink");
	}
	$("#navigation").slideToggle();
});