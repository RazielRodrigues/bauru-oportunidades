// let recognition = new webkitSpeechRecognition();
// recognition.lang = "pt-BR";
// recognition.continuous = true;
// recognition.interimResults = true;

// const newRecordButton = document.getElementById('new-record');
const saveRecordForm = document.getElementById('save-record');
const saveRecordButton = document.getElementById('save-record-button');
// const stopRecordButton = document.getElementById('stop-record');
// const resultArea = document.getElementById('result');
// const neonListening = document.getElementById('snackbar');

// stopRecordButton.disabled = true;

// speech = () => {
// 	recognition.start();
// 	recognition.onresult = function(e){
// 		for (var i = e.resultIndex; i < e.results.length; i++) {
// 			(e.results[i].isFinal) ? resultArea.value += e.results[i][0].transcript.trim().toLowerCase(): '' ;
// 		}
// 	}
// 	neonListening.className = 'show';
// 	saveRecordButton.disabled = false;
// 	newRecordButton.disabled = true;

// 	stopRecordButton.disabled = false;
// }

saveSpeech = (e) => {
	e.preventDefault();
	// recognition.stop();
	
	let form = new FormData(saveRecordForm);
	console.log(form.values);

	fetch("/create-record", {
		method: "POST",
		body: form
	})
	.then(res => res.json())
    .then(response => {
		alert(response.message);
	})
    .catch(err => {
		alert(err.message);
    });;	
}

// newRecordButton.addEventListener('click', speech);
// stopRecordButton.addEventListener('click', () => {
// 	neonListening.className = neonListening.className.replace("show", "")
// 	resultArea.value = '';
// 	recognition.stop();
// 	saveRecordButton.disabled = true;
// 	recognition.continuous = false;
// 	stopRecordButton.disabled = true;
// 	newRecordButton.disabled = false;


// });
saveRecordForm.addEventListener('submit', saveSpeech);
