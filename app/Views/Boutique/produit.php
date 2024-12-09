<div class="container my-5">
    <!-- Fil d'Ariane -->
    <div class="breadcrumb-container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?= site_url('/') ?>">Accueil</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?= site_url('boutique/categorie/' . $product['cat_name']) ?>">
                        <?= esc($product['cat_name']) ?>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?= esc($product['p_name']) ?>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Section produit -->
    <div class="row">
        <div class="col-md-5">
            <img src="<?= esc($product['img_path']) ?>" class="img-fluid product-image" alt="<?= esc($product['p_name']) ?>" style="max-height: 500px; object-fit: cover;">
        </div>
        <div class="col-md-6 product-card-produit">
            <h1 class="mb-3 product-title texte-doree"><?= esc($product['p_name']) ?></h1>
            <h4 class="product-price mb-4"><?= $product['p_price'] . '€' ?></h4>
            <p class="product-description mb-4"><?= esc($product['description']) ?></p>
            <div class="trait-doree-produit"></div>

            <form action="<?= site_url('/panier/ajouter/' . $product['id_prod']) ?>" method="post">
                <div class="d-flex align-items-center mb-3">
                    <div class="input-group" style="width: 160px;">
                        <button class="btn btn-outline-dark btn-sm" type="button" onclick="updateValue(this, -1)">-</button>
                        <input type="number" class="form-control text-center" name="quantity" value="1" min="1" max="10" readonly>
                        <button class="btn btn-outline-dark btn-sm" type="button" onclick="updateValue(this, 1)">+</button>
                    </div>
                    <button class="btn btn-primary ms-3 btn-gold-hover">
                        <i class="bi bi-cart"></i> Ajouter au panier
                    </button>
                </div>
            </form>
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
