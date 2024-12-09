<div class="page-container mt-5">
	<h1>Ajouter un Article</h1>
	<form action="/admin/blog/ajouter" method="post" enctype="multipart/form-data">



	        <!-- Formulaire d'upload -->
		<div class="mb-3">
            <label for="new_img" class="form-label">Uploader une nouvelle image :</label>
            <input type="file" id="new_img" name="new_img" class="form-control" accept="image/*">
            <button id="uploadBtn" type="button" class="btn btn-primary mt-2">Uploader</button>
        </div>

        <div class="mb-3">
            <label class="form-label">Choisir une ou plusieurs images :</label>
            <div id="media-library" class="row">
                <?php if (isset($images)): ?>
                    <!-- Boucle PHP pour afficher les images -->
                    <?php foreach ($images as $image): ?>
                        <div class="col-md-3">
                            <div class="card image-card" data-id="<?= $image['id_img'] ?>">
                                <img src="<?= $image['img_path'] ?>" alt="<?= $image['img_name'] ?>" class="card-img-top" style="cursor: pointer;">
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <small id="selected-count" class="text-muted">Images sélectionnées : 0</small>
            <input type="hidden" id="existing_imgs" name="existing_imgs">
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

<style>
    .image-card {
        border: 2px solid transparent;
        transition: border-color 0.3s;
    }

    .image-card:hover {
        border: 2px solid #007bff;
    }

    .border-primary {
        border: 10px solid #007bff !important;
    }

    .category-tag {
        display: inline-block;
        background-color: #007bff;
        color: white;
        padding: 5px 10px;
        margin: 3px;
        border-radius: 15px;
        font-size: 14px;
        position: relative;
    }

    .category-tag span {
        margin-left: 8px;
        cursor: pointer;
        color: #fff;
        font-weight: bold;
    }

    .category-tag span:hover {
        color: #000;
    }
</style>