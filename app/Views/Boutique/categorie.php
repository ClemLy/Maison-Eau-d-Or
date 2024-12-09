<div class="container my-5">
<h1 class="text-center mb-2 texte-doree" style="margin-top:100px;margin-bottom: 0; padding: 0;"><?= esc($category['cat_name']) ?></h1>


    <?php if (!empty($products)): ?>
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow border-0 product-card">
                        <a href="<?= site_url('boutique/produit/' . $product['id_prod']) ?>" class="text-decoration-none text-dark">
                            <img src="<?= esc($product['img_path']) ?>" class="card-img-top product-image" 
                                 alt="<?= esc($product['p_name']) ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body text-center">
                                <h5 class="card-title texte-doree"><?= esc($product['p_name']) ?></h5>
                                <p class="card-text" style="color: #555;"><strong>Prix:</strong> <?= esc($product['p_price']) ?> €</p>
                            </div>
                        </a>
                        <div class="card-footer bg-white border-0">
                            <form action="<?= site_url('/panier/ajouter/' . $product['id_prod']) ?>" method="post" class="d-flex align-items-center justify-content-center">
                                <div class="input-group" style="width: 150px;">
                                    <button class="btn btn-outline-dark btn-sm" type="button" onclick="updateValue(this, -1)">-</button>
                                    <input type="number" class="form-control text-center" name="quantity" value="1" min="1" max="10" readonly>
                                    <button class="btn btn-outline-dark btn-sm" type="button" onclick="updateValue(this, 1)">+</button>
                                </div>
                                <button class="btn btn-dark ms-3 btn-gold-hover">
                                    <i class="bi bi-cart"></i> Ajouter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center" style="color: #555;">Aucun produit disponible pour cette catégorie.</p>
    <?php endif; ?>
</div>

<!-- JavaScript -->
<script>
    function updateValue(button, step) {
        const inputGroup = button.closest('.input-group');
        const input = inputGroup.querySelector('input[type="number"]');
        let value = parseInt(input.value);
        const newValue = value + step;
        if (newValue >= parseInt(input.min) && newValue <= parseInt(input.max)) {
            input.value = newValue;
        }
    }
</script>
