<div class="container my-5">
<h1 class="text-center mb-2 text-gold" style="margin-top:100px;margin-bottom: 0; padding: 0;"><?= esc($category['cat_name']) ?></h1>
    <div class="d-flex justify-content-center" style="margin-bottom:25px;margin-top: -5px;">
        <hr class="trait-doree">
    </div>



    <?php if (!empty($products)): ?>
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow border-0 product-card">
                        <a href="<?= site_url('boutique/produit/' . $product['id_prod']) ?>" class="text-decoration-none text-dark">
                            <img src="<?= esc($product['img_path']) ?>" class="card-img-top product-image" 
                                 alt="<?= esc($product['p_name']) ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body text-center">
                                <h5 class="card-title text-gold-gradient"><?= esc($product['p_name']) ?></h5>
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

<!-- Custom Styles -->
<style>
 /* Texte doré subtil */
    .text-gold {
        color: #d4af37;
        font-weight: bold;
    }

    .text-gold-gradient {
        text-decoration: none;
        background: linear-gradient(45deg, #d4af37, #b8860b, #c1a15a, #d4af37);
        -webkit-background-clip: text;
        color: transparent;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2), 0 0 5px rgba(0, 0, 0, 0.3);
    }

    /* Effet hover adouci */
    .product-card {
        border: 1px solid #f0f0f0;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 10px;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .product-image {
        transition: transform 0.2s ease, filter 0.2s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
        filter: brightness(0.95);
    }

    /* Bouton Ajouter */
    .btn-gold-hover {
        background: linear-gradient(45deg, #d4af37, #b8860b);
        color: #fff;
        border: none;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    .btn-gold-hover:hover {
        background: linear-gradient(45deg, #c1a15a, #d4af37);
        color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Titres */
    .card-title {
        font-size: 1.2rem;
        font-weight: bold;
    }

    .card-text {
        font-size: 1rem;
    }


    hr {
        border: none;
        height: 2px;
        background: #d4af37;
        width: 50%;
        margin: 0 auto;
    }

    .trait-doree{
        margin-bottom:50px;
        width: 150px; 
        height: 3px; 
        background: 
        linear-gradient(45deg, #d4af37, #b8860b, #c1a15a, #d4af37); 
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2), 0 0 5px rgba(0, 0, 0, 0.3);
        border-radius: 2px;
        opacity: 0.5;
        margin: 0 auto;
}
</style>

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
