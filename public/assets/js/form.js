const saveRecordForm = document.getElementById('save-job');
const saveRecordButton = document.getElementById('save-job-button');

const saveForm = async (e) => {
	e.preventDefault();

	// Desabilita botão durante requisição
	saveRecordButton.disabled = true;

	try {
		let form = new FormData(saveRecordForm);

		const response = await fetch("/create-record", {
			method: "POST",
			body: form
		});

		// Verifica se a resposta foi bem-sucedida
		if (!response.ok) {
			throw new Error(`Erro HTTP: ${response.status}`);
		}

		await response.json();
		window.location.reload();
	} catch (err) {
		console.error('Erro na requisição:', err);
		alert(err.message || 'Erro ao salvar registro');
	} finally {
		// Reabilita botão
		saveRecordButton.disabled = false;
	}
}

saveRecordForm.addEventListener('submit', saveForm);