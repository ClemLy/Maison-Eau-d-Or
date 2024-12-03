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

        <!-- Image -->
        <div class="mb-3">
            <label for="id_img" class="form-label">Image :</label>
            <input type="file" id="id_img" name="id_img" class="form-control" accept="image/*" required>
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

        <!-- Bouton d'envoi -->
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Ajouter le produit</button>
        </div>
    </form>
</div>