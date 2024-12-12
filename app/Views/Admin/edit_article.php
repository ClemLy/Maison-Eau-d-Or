<div class="page-container">
	<h1>Modifier Blog</h1>
</div>



<form method="post" action="<?= site_url('admin/blog/modifier/')?>">
	<!-- Formulaire d'upload -->
	<div class="form-container" style="max-width: 60%;">
		<div class="mb-3">
			<label for="art_title" class="form-label">Titre de l'article</label>
			<input type="text" id="art_title" name="art_title" class="form-control"
				value="<?= isset($article['art_title']) ? esc($article['art_title']) : '' ?>" required>
		</div>

		<div class="mb-3">
			<label for="new_img" class="form-label">Uploader une nouvelle image :</label>
			<input type="file" id="new_img" name="new_img" class="form-control" accept="image/*">
			<button id="uploadBtn" type="button" class="btn btn-black mt-2" style="max-width: 20%;">Uploader</button>
		</div>

		<div class="mb-3">
			<label class="form-label">Choisir une image :</label>
			<div id="media-library" class="row">
				<?php if (isset($images)): ?>
					<!-- Boucle PHP pour afficher toutes les images -->
					<?php foreach ($images as $image): ?>
						<div class="col-md-3">
							<div class="card image-card <?= $image['id_img'] == $article['id_img'] ? 'border-primary' : '' ?>"
								data-id="<?= $image['id_img'] ?>">
								<img src="<?= esc($image['img_path']) ?>"
									alt="<?= esc($image['img_name']) ?>"
									class="card-img-top"
									style="cursor: pointer;">
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<input type="hidden" id="existing_imgs" name="existing_imgs" value="<?= $article['id_img'] ?>">
		</div>
	</div>

	<div class="d-flex p-2">
		<!-- Conteneur de l'éditeur -->
		<div class="w-30">

			<div id="editor"><?= isset($currentContent) ? esc($currentContent, 'html') : '' ?></div>
			<input type="hidden" id="hiddenContent" name="content">
			<div class="page-content">
				<button id="saveBtn" class="btn btn-primary">Sauvegarder</button>
			</div>
			<div id="message" style="margin-top: 20px;"></div>
		</div>

		<!-- Section de prévisualisation -->
		<div class="page-container w-100 mt-0">
			<h1>Aperçu du Blog</h1>
			<div id="previewContent" class="page-content"></div>
		</div>
	</div>
	<input type="hidden" name="id_art" value="<?= $article['id_art'] ?>">

</form>

<!-- Ajout d'un événement de clic pour sélectionner une seule image -->
<script>
	document.querySelectorAll('.image-card').forEach(card => {
		card.addEventListener('click', function () {
			// Supprime la classe 'border-primary' de toutes les cartes
			document.querySelectorAll('.image-card').forEach(c => c.classList.remove('border-primary'));
			
			// Ajoute la classe 'border-primary' à la carte cliquée
			this.classList.add('border-primary');
			
			// Met à jour la valeur du champ caché avec l'ID de l'image sélectionnée
			document.getElementById('existing_imgs').value = this.dataset.id;
		});
	});
</script>

<!-- Charger Quill.js -->
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

<script>
	// Initialiser Quill
	const quill = new Quill('#editor', {
		theme: 'snow',
		placeholder: 'Écris ton texte ici...',
		modules: {
			toolbar: [
				[{ 'header': [2, false] }],
				['bold', 'italic', 'underline'], // Styles de texte
				[{ 'list': 'ordered' }, { 'list': 'bullet' }], // Listes
				['link'] // Alignement
			]
		}
	});

	// Charger le contenu existant dans Quill (HTML injecté dans #editor)
	const existingContent = <?= json_encode($currentContent) ?>;
	quill.clipboard.dangerouslyPasteHTML(existingContent);

	// Mise à jour de la prévisualisation
	quill.on('text-change', function () {
		const htmlContent = quill.root.innerHTML;
		document.getElementById('previewContent').innerHTML = htmlContent;
	});

	document.addEventListener('DOMContentLoaded', function () {
		const htmlContent = quill.root.innerHTML;
		document.getElementById('previewContent').innerHTML = htmlContent;
	});

	// Sauvegarder le contenu dans un champ caché avant soumission
	document.getElementById('saveBtn').addEventListener('click', function () {
		const htmlContent = quill.root.innerHTML;
		document.getElementById('hiddenContent').value = htmlContent;

		// Envoyer les données via fetch ou soumettre un formulaire
		fetch('<?= site_url('admin/a-propos/modifier') ?>', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
				'X-Requested-With': 'XMLHttpRequest',
			},
			body: new URLSearchParams({ content: htmlContent }).toString(),
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