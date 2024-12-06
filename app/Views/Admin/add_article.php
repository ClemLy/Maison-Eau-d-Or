<div class="page-container mt-5">
	<h1>Ajouter un Article</h1>
	<form action="/admin/blog/ajouter" method="post" enctype="multipart/form-data">
		<!-- Choix de l'image -->
		<div class="mb-3">
			<label class="form-label">Choisir une image existante :</label>
			<div class="row">
				<?php if (isset($images)): ?>
					<?php foreach ($images as $image): ?>
						<div class="col-md-3">
							<div class="card image-card" data-id="<?= $image['id_img'] ?>">
								<img src="<?= $image['img_path'] ?>" alt="<?= $image['img_name'] ?>" class="card-img-top" style="cursor: pointer;">
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<input type="hidden" id="existing_img" name="existing_img">
		</div>

		<div class="mb-3">
			<label for="new_img" class="form-label">Ou uploader une nouvelle image :</label>
			<input type="file" id="new_img" name="new_img" class="form-control" accept="image/*">
		</div>

		<!-- Titre de l'article -->
		<div class="mb-3">
			<label for="title" class="form-label">Titre de l'article</label>
			<input type="text" id="title" name="title" class="form-control" required>
		</div>

		<!-- Texte de l'article -->
		<div class="mb-3">
			<label for="content" class="form-label">Contenu</label>
			<div id="editor" style="height: 300px;"></div>
			<input type="hidden" id="hiddenContent" name="content">
		</div>

		<button type="submit" class="btn btn-primary">Publier l'article</button>
	</form>
</div>

<!-- Quill.js -->
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

<script>
	const quill = new Quill('#editor', {
		theme: 'snow',
		placeholder: 'Rédigez votre article ici...',
		modules: {
			toolbar: [
				[{ 'header': [2, false] }],
				['bold', 'italic', 'underline'], 
				[{ 'list': 'ordered' }, { 'list': 'bullet' }],
				['link']
			]
		}
	});

	document.querySelector('form').addEventListener('submit', function () {
		document.getElementById('hiddenContent').value = quill.root.innerHTML;
	});

	document.querySelectorAll('.image-card').forEach(card => {
		card.addEventListener('click', () => {
			document.getElementById('existing_img').value = card.getAttribute('data-id');
			document.querySelectorAll('.image-card').forEach(c => c.classList.remove('selected'));
			card.classList.add('selected');
		});
	});
</script>

<style>
	.image-card.selected {
		border: 2px solid #007bff;
	}
</style>