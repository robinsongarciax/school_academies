$(document).ready(function() {
	$("#student-form").submit(function() {
		var student_user = $('#student-user').val().toUpperCase();
		console.log('Algo:'+student_user);
		$('#student-user').val(student_user);
		$("#student-pass").val(student_user);
	});

	$("#student-tmp-form").on('submit', function(event) {
		var student_user = $('#student-user').val().toUpperCase();
		$('#student-user').val(student_user);
		$("#student-pass").val(student_user);
	});
});