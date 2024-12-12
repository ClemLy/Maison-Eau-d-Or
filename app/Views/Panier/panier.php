<div class="page-container">
    <h1 class="mb-4 text-center text-gold">Votre Panier</h1>

    <?php if (session()->get('danger')): ?>
        <div class="alert alert-danger text-center">
            <?= session()->get('danger') ?>
        </div>
    <?php endif; ?>

    <div class="page-content">
        <?php if (!empty($cartItems)): ?>
            <div class="row">
                <?php foreach ($cartItems as $item): ?>
                    <div class="col-12 mb-4">
                        <div class="row g-0">
                            <div class="col-md-3">
                                <img src="<?= esc($item['img_path']) ?>" class="img-fluid rounded-start" alt="Produit">
                            </div>
                            <div class="col-md-9">
                                <div class="card-body-cart">
                                    <h5 class="card-title"><?= esc($item['p_name']) ?></h5>
                                    <p class="card-text text-muted">Prix : <?= number_format($item['p_price'], 2, ',', ' ') ?> €</p>

                                    <div class="input-group" style="width: 150px;">
                                        <button class="btn btn-outline-dark btn-sm" type="button" onclick="updateValueSideMenu(this, -1, <?= $item['id_prod'] ?>)">-</button>
                                        <input type="number" class="form-control text-center" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="99" readonly>
                                        <button class="btn btn-outline-dark btn-sm" type="button" onclick="updateValueSideMenu(this, 1, <?= $item['id_prod'] ?>)">+</button>
                                    </div>

                                    <p class="card-text text-muted">Total : <?= number_format($item['p_price'] * $item['quantity'], 2, ',', ' ') ?> €</p>

                                    <a href="<?= site_url('panier/supprimer/' . $item['id_prod']) ?>" class="text-end btn btn-supprimer_panier mb-3" style="border: none;">
                                        <i class="bi bi-trash"></i> Supprimer
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Total et options en bas -->
            <div class="row mt-4">
                <div class="col-12 text-end">
                    <p class="fs-4"><strong>Total du Panier : </strong> <?= number_format(array_reduce($cartItems, function ($sum, $item) {
                                                                            return $sum + $item['p_price'] * $item['quantity'];
                                                                        }, 0), 2, ',', ' ') ?> €</p>
                    <div class="btn-group">
                        <a href="<?= site_url('panier/vider') ?>" class="btn btn-dark ms-3 btn-gold-hover">
                            <i class="bi bi-trash"></i> Vider le Panier
                        </a>
                        <a href="<?= site_url('commander') ?>" class="btn btn-dark ms-3 btn-gold-hover">
                            Passer la Commande
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">Votre panier est vide.</div>
        <?php endif; ?>
    </div>
</div>

<style>
    .card-body-cart {
        background-color: #f9f9f9;
        border-radius: 8px;
        color: #333;
        padding: 10px;
    }

    .img-fluid {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }


.btn-supprimer_panier{
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    background: linear-gradient(45deg, #d4af37, #b8860b, #c1a15a);
    color: white;
    padding: 8px 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    font-size: 0.9rem;
    transition: background-color 0.3s ease, transform 0.2s ease;
}
</style>