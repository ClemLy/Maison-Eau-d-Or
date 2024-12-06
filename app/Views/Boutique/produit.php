<style>
    /* Texte doré subtil */
    .text-gold {
        color: #d4af37;
        font-weight: bold;
        font-size: 2rem;
        font-weight: 700;
    }

    .text-gold-gradient {
        text-decoration: none;
        background: linear-gradient(45deg, #d4af37, #b8860b, #c1a15a, #d4af37);
        -webkit-background-clip: text;
        color: transparent;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2), 0 0 5px rgba(0, 0, 0, 0.3);
    }

    /* Styles de la carte produit */
    .product-card {
        border: 1px solid #f0f0f0;
        border-radius: 15px;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Effet image produit */
    .product-image {
        transition: transform 0.3s ease, filter 0.3s ease;
        border-radius: 15px;
    }

    /* Boutons d'ajout au panier */
    .btn-gold-hover {
        background: linear-gradient(45deg, #d4af37, #b8860b);
        color: #fff;
        border: none;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        border-radius: 5px;
    }

    .btn-gold-hover:hover {
        background: linear-gradient(45deg, #c1a15a, #d4af37);
        color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .product-price {
        font-size: 1.5rem;
        font-weight: 600;
    }

    .product-description {
        font-size: 1rem;
        color: #666;
        line-height: 1.6;
    }

    /* Boutons + et - avec classes Bootstrap */
    .btn-quantity {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
        border-radius: 5px;
    }

    /* Séparateur doré */
    .trait-doree {
        margin: 50px auto;
        width: 85%;
        height: 3px;
        background: linear-gradient(45deg, #d4af37, #b8860b, #c1a15a, #d4af37);
        border-radius: 2px;
        opacity: 0.5;
    }

    /* Style général */
    .container {
        background-color: #f9f9f9;
        padding: 30px 0;
    }

    .row {
        margin: 0;
    }

    .col-md-6 {
        padding: 20px;
    }
</style>

<div class="container my-5 d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="row">
        <div class="col-md-6">
            <img src="<?= esc($product['img_path']) ?>" class="img-fluid product-image" alt="<?= esc($product['p_name']) ?>" style="max-height: 500px; object-fit: cover;">
        </div>
        <div class="col-md-6 product-card">
            <h1 class="mb-3 product-title text-gold"><?= esc($product['p_name']) ?></h1>
            <h4 class="product-price mb-4"><?= $product['p_price'] . '€' ?></h4>
            <p class="product-description mb-4"><?= esc($product['description']) ?></p>
            <div class="trait-doree"></div>

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
