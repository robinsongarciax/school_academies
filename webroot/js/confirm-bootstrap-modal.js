

function addToModal(formName) {
	const modal = document.getElementById('confirmRegistrationModal');

	modal.dataset.formName = formName;
}

const accept = document.getElementById('accept');

accept.addEventListener('click', function() {
	const modal = document.getElementById('confirmRegistrationModal');

	formName = modal.dataset.formName; // data-form-name => formName;
	if (formName) {
		document[formName].submit();
	}
});

$('#confirmRegistrationModal').on('show.bs.modal', function (event) {
	const link = event.relatedTarget;
  	const message = link.dataset.confirmMessage;
  	const confirmMessage = document.getElementById('confirmMessage');
  	confirmMessage.textContent = message;
})

$('#noAvailabilityModal').on('show.bs.modal', function (event) {
	const link = event.relatedTarget;
  	const message = link.dataset.confirmMessage;
  	const confirmMessage = document.getElementById('confirmMessage2');
  	confirmMessage.textContent = message;
})