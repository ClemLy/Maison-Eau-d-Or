<div class="container mt-5">
    <h1 class="text-center mb-4">Ajouter un produit</h1>

    <form action="/admin/produit/ajouter" method="post" enctype="multipart/form-data" class="p-4 border rounded shadow-sm">
        <!-- Nom du produit -->
        <div class="mb-3">
            <label for="p_name" class="form-label">Nom du produit :</label>
            <input type="text" id="p_name" name="p_name" class="form-control" required>
        </div>

        <!-- Prix du produit -->
        <div class="mb-3">
            <label for="p_price" class="form-label">Prix :</label>
            <input type="number" id="p_price" name="p_price" class="form-control" step="0.01" min="0" required>
        </div>

        <!-- Description du produit -->
        <div class="mb-3">
            <label for="description" class="form-label">Description :</label>
            <textarea id="description" name="description" class="form-control" rows="5" required></textarea>
        </div>

        <!-- Catégories -->
        <label for="categories-container" class="form-label">Catégories :</label>
        <div class="input-group mb-3">
            <input type="text" id="new-category" class="form-control" placeholder="Nouvelle catégorie">
            <button type="button" id="add-category-btn" class="btn btn-primary">Ajouter</button>
        </div>

        <div class="mb-3">
            <div id="categories-container" class="border p-2 rounded text-muted" style="opacity:80%!important;min-height: 40px;">
                <!-- Les catégories saisies seront ajoutées ici -->
            </div>
            <input class="text-muted" type="hidden" id="categories" name="categories">
            <small class="text-muted">Ajoutez une catégorie, puis appuyez sur "Ajouter". Utilisez la croix pour en retirer.</small>
        </div>

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

        <!-- Activer dans la boutique -->
        <div class="form-check mb-3">
            <input type="checkbox" id="on_sale" name="on_sale" class="form-check-input" value="1">
            <label for="on_sale" class="form-check-label">Activer sur la boutique ?</label>
        </div>

        <!-- Produit mis en avant -->
        <div class="form-check mb-3">
            <input type="checkbox" id="is_star" name="is_star" class="form-check-input" value="1">
            <label for="is_star" class="form-check-label">Produit vedette</label>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Ajouter le produit</button>
        </div>
    </form>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imageCards = document.querySelectorAll('.image-card');
        const mediaLibrary = document.getElementById('media-library');
        const existingImgsInput = document.getElementById('existing_imgs');
        const selectedCountElement = document.getElementById('selected-count');

        // Gérer la sélection multiple d'images
        mediaLibrary.addEventListener('click', function (e) {
            const card = e.target.closest('.image-card');
            if (card) {
                card.classList.toggle('border-primary'); // Ajouter/retirer la bordure
                const selectedId = card.getAttribute('data-id');
                let selectedIds = existingImgsInput.value ? existingImgsInput.value.split(',') : [];

                if (selectedIds.includes(selectedId)) {
                    selectedIds = selectedIds.filter(id => id !== selectedId);
                } else {
                    selectedIds.push(selectedId);
                }

                existingImgsInput.value = selectedIds.join(',');
                selectedCountElement.textContent = `Images sélectionnées : ${selectedIds.length}`;
            }
        });

        // Gérer l'upload d'image via AJAX
        document.getElementById('uploadBtn').addEventListener('click', function () {
            const fileInput = document.getElementById('new_img');
            const file = fileInput.files[0];

            if (!file) {
                alert('Veuillez sélectionner une image à uploader.');
                return;
            }

            // Vérification du type et de la taille du fichier
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Seules les images JPEG, PNG et GIF sont acceptées.');
                return;
            }

            if (file.size > 5 * 1024 * 1024) { // Limite de 5 Mo
                alert('L\'image ne doit pas dépasser 5 Mo.');
                return;
            }

            const formData = new FormData();
            formData.append('new_img', file);

            fetch('/image/upload', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Ajouter l'image uploadée dans la médiathèque
                        const newCard = document.createElement('div');
                        newCard.classList.add('col-md-3');
                        newCard.innerHTML = `
                        <div class="card image-card" data-id="${data.image.id_img}">
                            <img src="${data.image.img_path}" alt="${data.image.img_name}" class="card-img-top" style="cursor: pointer;">
                        </div>
                    `;
                        mediaLibrary.appendChild(newCard);

                        // Réinitialiser le champ fichier
                        fileInput.value = '';

                        // Ajouter l'événement de sélection pour la nouvelle image
                        newCard.querySelector('.image-card').addEventListener('click', function () {
                            const card = this;
                            card.classList.toggle('border-primary');
                            const selectedId = card.getAttribute('data-id');
                            let selectedIds = existingImgsInput.value ? existingImgsInput.value.split(',') : [];

                            if (selectedIds.includes(selectedId)) {
                                selectedIds = selectedIds.filter(id => id !== selectedId);
                            } else {
                                selectedIds.push(selectedId);
                            }

                            existingImgsInput.value = selectedIds.join(',');
                            selectedCountElement.textContent = `Images sélectionnées : ${selectedIds.length}`;
                        });
                    } else {
                        alert('Erreur lors de l\'upload : ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de l\'upload.');
                });
        });

        // Gestion des catégories
        const container = document.getElementById('categories-container');
        const input = document.getElementById('categories');
        const newCategoryInput = document.getElementById('new-category');
        const addCategoryBtn = document.getElementById('add-category-btn');
        let categories = [];

        function addCategory(category) {
            category = category.trim();
            if (!category || categories.includes(category)) {
                alert('Cette catégorie est déjà ajoutée ou est invalide.');
                return;
            }

            categories.push(category);

            // Ajout visuel
            const tag = document.createElement('div');
            tag.classList.add('category-tag');
            tag.textContent = category;

            // Bouton de suppression
            const removeButton = document.createElement('span');
            removeButton.textContent = '×';
            removeButton.addEventListener('click', function () {
                categories = categories.filter(c => c !== category);
                input.value = JSON.stringify(categories); // Met à jour le champ caché
                console.log('Catégories après suppression :', categories);
                container.removeChild(tag);
            });

            tag.appendChild(removeButton);
            container.appendChild(tag);

            // Mettre à jour le champ caché
            input.value = JSON.stringify(categories);
            console.log('Catégories mises à jour :', categories);
            newCategoryInput.value = '';
        }

        addCategoryBtn.addEventListener('click', function () {
            const category = newCategoryInput.value;
            if (category) addCategory(category);
        });

        newCategoryInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const category = newCategoryInput.value;
                if (category) addCategory(category);
            }
        });
    });
</script>
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