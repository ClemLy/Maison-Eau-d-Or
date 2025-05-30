<div class="container mt-5">
    <h1 class="text-center mb-4">Ajouter un produit</h1>

    <div class="form-container mt-2" style="max-width: 80%;">
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
            <div class="input-group mb-3 align-items-baseline">
                <input type="text" id="new-category" class="form-control me-3" style="max-width: 20%" placeholder="Nouvelle catégorie">
                <button type="button" id="add-category-btn" class="btn btn-black p-0" style="width:40px; height: 40px;">+</button>
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
                <button id="uploadBtn" type="button" class="btn btn-black mt-2" style="max-width: 20%;">Uploader</button>
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
</div>


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