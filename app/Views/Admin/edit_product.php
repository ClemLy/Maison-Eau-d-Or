<div class="container mt-5">
    <h1 class="text-center mb-4">Modifier le produit</h1>

    <form action="/admin/produit/modifier" method="post" enctype="multipart/form-data" class="p-4 border rounded shadow-sm">
        <!-- ID du produit -->
        <input type="hidden" name="id_prod" value="<?= esc($product['id_prod']) ?>">

        <!-- Nom du produit -->
        <div class="mb-3">
            <label for="p_name" class="form-label">Nom du produit :</label>
            <input type="text" id="p_name" name="p_name" class="form-control" value="<?= esc($product['p_name']) ?>" required>
        </div>

        <!-- Prix du produit -->
        <div class="mb-3">
            <label for="p_price" class="form-label">Prix :</label>
            <input type="number" id="p_price" name="p_price" class="form-control" step="0.01" min="0" value="<?= esc($product['p_price']) ?>" required>
        </div>

        <!-- Description du produit -->
        <div class="mb-3">
            <label for="description" class="form-label">Description :</label>
            <textarea id="description" name="description" class="form-control" rows="5" required><?= esc($product['description']) ?></textarea>
        </div>

        <!-- Catégories -->
        <label for="categories-container" class="form-label">Catégories :</label>
        <div class="input-group mb-3">
            <input type="text" id="new-category" class="form-control" placeholder="Nouvelle catégorie">
            <button type="button" id="add-category-btn" class="btn btn-primary">Ajouter</button>
        </div>

        <div class="mb-3">
            <div id="categories-container" class="border p-2 rounded text-muted" style="opacity:80%!important;min-height: 40px;">
                <!-- Les catégories ajoutées par JS apparaîtront ici -->
            </div>
            <input type="hidden" id="categories" name="categories" value="">
            <small class="text-muted">Ajoutez une catégorie, puis appuyez sur "Ajouter". Utilisez la croix pour en retirer.</small>
        </div>

        <!-- Médiathèque : Sélection d'une image existante -->
        <?php if (isset($images)): ?>
        <div class="mb-3">
            <label class="form-label">Choisir une image existante :</label>
            <div class="row">
                <?php foreach ($images as $image): ?>
                    <div class="col-md-3">
                        <div class="card image-card <?= $image['id_img'] == $product['id_img'] ? 'border-primary' : '' ?>" data-id="<?= $image['id_img'] ?>">
                            <img src="<?= $image['img_path'] ?>" alt="<?= $image['img_name'] ?>" class="card-img-top" style="cursor: pointer;">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <input type="hidden" id="existing_img" name="existing_img" value="<?= esc($product['id_img']) ?>">
        </div>
        <?php endif; ?>

        <!-- Nouvelle image -->
        <div class="mb-3">
            <label for="new_img" class="form-label">Ou uploader une nouvelle image :</label>
            <input type="file" id="new_img" name="new_img" class="form-control" accept="image/*">
        </div>

        <!-- Activer dans la boutique -->
        <div class="form-check mb-3">
            <input type="checkbox" id="on_sale" name="on_sale" class="form-check-input" value="t" <?= $product['on_sale'] === 't' ? 'checked' : '' ?>>
            <label for="on_sale" class="form-check-label">Activer sur la boutique ?</label>
        </div>

        <!-- Produit mis en avant -->
        <div class="form-check mb-3">
            <input type="checkbox" id="is_star" name="is_star" class="form-check-input" value="t" <?= $product['is_star'] === 't' ? 'checked' : '' ?>>
            <label for="is_star" class="form-check-label">Produit vedette</label>
        </div>

        <!-- Bouton d'envoi -->
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Modifier le produit</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imageCards = document.querySelectorAll('.image-card');

        imageCards.forEach(card => {
            card.addEventListener('click', function () {
                // Retirer la classe active de toutes les images
                imageCards.forEach(c => c.classList.remove('border-primary'));

                // Ajouter une bordure pour indiquer la sélection
                this.classList.add('border-primary');

                // Mettre à jour la valeur de l'input caché
                const selectedId = this.getAttribute('data-id');
                document.getElementById('existing_img').value = selectedId;
            });
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
</style>

<style>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('categories-container');
        const input = document.getElementById('categories');
        const newCategoryInput = document.getElementById('new-category');
        const addCategoryBtn = document.getElementById('add-category-btn');

        // Catégories existantes transmises depuis PHP
        const initialCategories = <?= json_encode($product['categories'] ?? []) ?>;

        // Tableau pour suivre les catégories ajoutées
        let categories = [...initialCategories];

        // Fonction pour afficher les catégories dans le conteneur
        function renderCategories() {
            // Vider le conteneur
            container.innerHTML = '';

            // Ajouter chaque catégorie
            categories.forEach(category => {
                const tag = document.createElement('div');
                tag.classList.add('category-tag');
                tag.textContent = category;

                // Bouton de suppression
                const removeButton = document.createElement('span');
                removeButton.textContent = ' ×';
                removeButton.style.cursor = 'pointer';
                removeButton.style.marginLeft = '10px';
                removeButton.addEventListener('click', function () {
                    removeCategory(category);
                });

                tag.appendChild(removeButton);
                container.appendChild(tag);
            });

            // Mettre à jour l'input caché
            input.value = JSON.stringify(categories);
        }

        // Ajouter une catégorie
        function addCategory(category) {
            category = category.trim();
            if (category && !categories.includes(category)) {
                categories.push(category);
                renderCategories();
            }
        }

        // Supprimer une catégorie
        function removeCategory(category) {
            categories = categories.filter(c => c !== category);
            renderCategories();
        }

        // Ajouter une catégorie via le bouton
        addCategoryBtn.addEventListener('click', function () {
            const category = newCategoryInput.value;
            if (category) {
                addCategory(category);
                newCategoryInput.value = ''; // Réinitialiser le champ
            }
        });

        // Ajouter une catégorie via la touche "Entrée"
        newCategoryInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const category = newCategoryInput.value;
                if (category) {
                    addCategory(category);
                    newCategoryInput.value = ''; // Réinitialiser le champ
                }
            }
        });

        // Initialiser les catégories existantes
        renderCategories();
    });
</script>