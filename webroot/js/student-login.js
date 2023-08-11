$(document).ready(function() {
	$("#student-form").submit(function() {
		$("#student-pass").val($("#student-user").val());
	});
});