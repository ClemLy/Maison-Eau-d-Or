<div class="apropos-container">
	<h1>Modifier À Propos</h1>
</div>

<div id="editor"><?= isset($currentContent) ? esc($currentContent, 'html') : '' ?></div>
<input type="hidden" id="hiddenContent" name="content">
<button id="saveBtn" class="btn btn-primary">Sauvegarder</button>
<div id="message" style="margin-top: 20px;"></div>

<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

<script>
const quill = new Quill('#editor', {
	theme: 'snow',
	placeholder: 'Écris ton texte ici...',
	modules: {
		toolbar: [
			['bold', 'italic', 'underline'], // Styles de texte
			[{ 'list': 'ordered' }, { 'list': 'bullet' }], // Listes
			[{ 'align': [] }] // Alignement
		]
	}
});

// Bouton Sauvegarder
document.getElementById('saveBtn').addEventListener('click', function () {
	const htmlContent = quill.root.innerHTML;

	fetch('<?= site_url('admin/a-propos/modifier') ?>', {
	method: 'POST',
	headers: {
		'Content-Type': 'application/json',
		'X-Requested-With': 'XMLHttpRequest',
	},
	body: JSON.stringify({ content: htmlContent }),
	})
	.then(response => response.json())
	.then(data => {
		document.getElementById('message').innerHTML = `<div class="alert alert-success">${data.message}</div>`;
	})
	.catch(error => {
		document.getElementById('message').innerHTML = `<div class="alert alert-danger">Erreur : ${error.message}</div>`;
	});
});
</script>