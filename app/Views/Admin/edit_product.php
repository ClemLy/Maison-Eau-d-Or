<div class="container mt-5">
    <h1 class="text-center mb-4">Modifier un produit</h1>

    <form action="/admin/produit/modifier" method="post" enctype="multipart/form-data" class="p-4 border rounded shadow-sm">
        <!-- Nom du produit -->
        <div class="mb-3">
            <label for="p_name" class="form-label">Nom du produit :</label>
            <input type="text" id="p_name" name="p_name" class="form-control" value="<?= esc($product['p_name']) ?>" required>
        </div>

        <
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
                <!-- Afficher les catégories existantes -->
                <?php if (!empty($product['categories'])): ?>
                    <?php foreach ($product['categories'] as $category): ?>
                        <div class="category-tag">
                            <?= esc($category) ?>
                            <span class="remove-category" data-name="<?= esc($category) ?>">×</span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <input type="hidden" id="categories" name="categories" value='<?= json_encode($product['categories']) ?>'>
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
                <?php
                // Préparer les IDs des images associées au produit
                $productImages = array_column($product['images'], 'id_img');
                ?>
                <?php if (isset($images)): ?>
                    <!-- Boucle PHP pour afficher toutes les images -->
                    <?php foreach ($images as $image): ?>
                        <div class="col-md-3">
                            <div class="card image-card <?= in_array($image['id_img'], $productImages) ? 'border-primary' : '' ?>"
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
            <small id="selected-count" class="text-muted">Images sélectionnées : <?= count($productImages) ?></small>
            <input type="hidden" id="existing_imgs" name="existing_imgs" value="<?= implode(',', $productImages) ?>">
        </div>

        <!-- Activer dans la boutique -->
        <div class="form-check mb-3">
            <input type="checkbox" id="on_sale" name="on_sale" class="form-check-input" value="1" <?= $product['on_sale'] ? 't' : '' ?>>
            <label for="on_sale" class="form-check-label">Activer sur la boutique ?</label>
        </div>

        <!-- Produit mis en avant -->
        <div class="form-check mb-3">
            <input type="checkbox" id="is_star" name="is_star" class="form-check-input" value="1" <?= $product['is_star'] ? 't' : '' ?>>
            <label for="is_star" class="form-check-label">Produit vedette</label>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Modifier le produit</button>
        </div>

        <input type="hidden" name="id_prod" value="<?= $product['id_prod'] ?>">
    </form>
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