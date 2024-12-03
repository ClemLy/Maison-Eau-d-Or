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

        <!-- Médiathèque : Sélection d'une image existante -->
        <div class="mb-3">
            <label class="form-label">Choisir une image existante :</label>
            <div class="row">
                <!-- Boucle PHP pour afficher les images -->
                <?php foreach ($images as $image): ?>
                    <div class="col-md-3">
                        <div class="card image-card" data-id="<?= $image['id_img'] ?>">
                            <img src="<?= $image['img_path'] ?>" alt="<?= $image['img_name'] ?>" class="card-img-top" style="cursor: pointer;">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <input type="hidden" id="existing_img" name="existing_img">
        </div>


        <!-- Nouvelle image -->
        <div class="mb-3">
            <label for="new_img" class="form-label">Ou uploader une nouvelle image :</label>
            <input type="file" id="new_img" name="new_img" class="form-control" accept="image/*">
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