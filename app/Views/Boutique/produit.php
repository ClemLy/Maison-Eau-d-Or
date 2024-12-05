<div class="container my-5 d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="row">
        <div class="col-md-6">
            <img src="<?= esc($product['img_path']) ?>" class="img-fluid" alt="<?= esc($product['p_name']) ?>" style="max-height: 500px; object-fit: cover;">
        </div>
        <div class="col-md-6">
            <h1 class="mb-3"><?= esc($product['p_name']) ?></h1>
            <h4 class="texte-doree mb-4"><?= $product['p_price'] . '€' ?></h4>
            <p class="mb-4"><?= esc($product['description']) ?></p>

            <form action="<?= site_url('/panier/ajouter/' . $product['id_prod']) ?>" method="post">
                <div class="d-flex align-items-center mb-3">
                    <div class="input-group" style="width: 150px;">
                        <button class="btn btn-outline-primary" type="button" onclick="updateValue(this, -1)">-</button>
                        <input type="number" class="form-control text-center" name="quantity" value="1" min="1" max="10" readonly>
                        <button class="btn btn-outline-primary" type="button" onclick="updateValue(this, 1)">+</button>
                    </div>
                    <button class="btn btn-primary ms-3">
                        <i class="bi bi-cart"></i> Ajouter au panier
                    </button>
                </div>
            </form>


            <!-- <div class="d-flex align-items-center mb-3">
                <a href="<?= site_url('commander') ?>" class="btn btn-success ms-3" type="submit">
                    Acheter
                </a>
            </div> -->
            
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
