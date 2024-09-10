$(document).ready(function() {
	$("#student-form").submit(function() {
		var student_user = $('#student-user').val().toUpperCase();
		$('#student-user').val(student_user);
		$("#student-pass").val(student_user);
	});
});