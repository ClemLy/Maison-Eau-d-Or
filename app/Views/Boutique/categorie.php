<div class="container my-5">
    <h1 class="text-center mb-4"><?= esc($category['cat_name']) ?></h1>

    <?php if (!empty($products)): ?>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card h-100">
                        <a href="<?= site_url('boutique/produit/' . $product['id_prod']) ?>" class="text-decoration-none text-dark">
                            <img src="<?= esc($product['img_path']) ?>" class="card-img-top" alt="<?= esc($product['p_name']) ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= esc($product['p_name']) ?></h5>
                                <p class="card-text"><strong>Prix:</strong> <?= esc($product['p_price']) ?> €</p>
                            </div>
                        </a>
                        <div class="card-footer">
                            <form action="<?= site_url('/panier/ajouter/' . $product['id_prod']) ?>" method="post" class="d-flex align-items-center justify-content-center">
                                <div class="input-group" style="width: 150px;">
                                    <button class="btn btn-outline-primary" type="button" onclick="updateValue(this, -1)">-</button>
                                    <input type="number" class="form-control text-center" name="quantity" value="1" min="1" max="10" readonly>
                                    <button class="btn btn-outline-primary" type="button" onclick="updateValue(this, 1)">+</button>
                                </div>
                                <button class="btn btn-primary ms-3">
                                    <i class="bi bi-cart"></i> Ajouter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

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
