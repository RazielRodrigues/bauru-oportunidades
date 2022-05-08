const saveRecordForm = document.getElementById('save-job');
const saveRecordButton = document.getElementById('save-job-button');

saveSpeech = (e) => {
	e.preventDefault();

	let form = new FormData(saveRecordForm);

	fetch("/create-record", {
		method: "POST",
		body: form
	})
	.then(res => res.json())
    .then(response => {
		window.location.reload();
	})
    .catch(err => {
		alert(err.message);
    });;	
}

saveRecordForm.addEventListener('submit', saveSpeech);
