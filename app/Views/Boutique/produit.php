<div class="container my-5">

    <!-- Fil d'Ariane -->
    <div class="breadcrumb-container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?= site_url('/') ?>">Accueil</a>
                </li>
                <li class="breadcrumb-item">
     
                    <a href="<?= site_url('boutique/categorie/' . $product['categories'][0]) ?>">
                        <?= esc($product['categories'][0]) ?>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?= esc($product['p_name']) ?>
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-5">
            <img src="<?= esc($product['images'][0]['img_path']) ?>" class="img-fluid product-image" alt="<?= esc($product['p_name']) ?>" style="max-height: 500px; object-fit: cover;">
        </div>
        <div class="col-md-6 product-card-produit">
            <h1 class="mb-3 product-title texte-doree"><?= esc($product['p_name']) ?></h1>
            <h4 class="product-price mb-4"><?= $product['p_price'] . '€' ?></h4>
            <p class="product-description mb-4"><?= esc($product['description']) ?></p>
            <div class="trait-doree-produit"></div>

            <form action="<?= site_url('/panier/ajouter/' . $product['id_prod']) ?>" method="post" class="d-flex flex-row add-to-cart-form">
                <div class="d-flex align-items-center mb-3">
                    <div class="input-group" style="width: 160px;">
                        <button class="btn btn-outline-dark btn-sm" type="button" onclick="updateValue(this, -1)">-</button>
                        <input type="number" class="form-control text-center" name="quantity" value="1" min="1" max="10" readonly>
                        <button class="btn btn-outline-dark btn-sm" type="button" onclick="updateValue(this, 1)">+</button>
                    </div>
                    <button type="submit" class="btn btn-dark ms-3 btn-gold-hover" data-bs-toggle="offcanvas" data-bs-target="#panier_sideMenu">
                        <i class="bi bi-cart"></i> Ajouter au panier
                    </button>
                </div>

            </form>
        </div>
    </div>

    <hr>

    <div class="related-products mt-5">
        <h3 class="texte-doree">Produits connexes</h3>
        <div class="row">
            <?php if (!empty($relatedProducts)): ?>
                <?php foreach ($relatedProducts as $related): ?>
                    <div class="col-md-3 related-products-card">
                        <a href="<?= site_url('boutique/produit/' . $related['id_prod']) ?>">
                            <div class="card">
                                <img src="<?= esc($related['images'][0]['img_path']) ?>" class="card-img-top" alt="<?= esc($related['p_name']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= esc($related['p_name']) ?></h5>
                                    <p class="card-text"><?= number_format($related['p_price'], 2) ?> €</p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
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