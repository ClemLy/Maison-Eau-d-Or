<div class="offcanvas offcanvas-end" tabindex="-1" id="panier_sideMenu" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Votre Panier</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <?php if (!empty($cartItems)): ?>
            <div class="cart-items">
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item d-flex align-items-center mb-3">
                        <img src="<?= esc($item['img_path']) ?>" alt="Produit" class="cart-item-img">
                        <div class="cart-item-info ms-3">
                            <h6 class="cart-item-title mb-1"><?= esc($item['p_name']) ?></h6>
                            <p class="cart-item-price mb-1"><?= number_format($item['p_price'], 2, ',', ' ') ?> €</p>
                            <p class="cart-item-quantity">Qté : <?= $item['quantity'] ?></p>
                        </div>
                        <a href="<?= site_url('panier/supprimer/' . $item['id_prod']) ?>" class="btn-close ms-auto text-danger" aria-label="Remove"></a>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-total mt-4">
                <div class="d-flex justify-content-between">
                    <span>Total :</span>
                    <strong><?= number_format(array_reduce($cartItems, function ($sum, $item) {
                        return $sum + $item['p_price'] * $item['quantity'];
                    }, 0), 2, ',', ' ') ?> €</strong>
                </div>
            </div>

            <div class="mt-4">
                <a href="<?= site_url('panier') ?>" class="btn btn-gold-hover w-100 text-dark">Afficher details</a>
                <a href="<?= site_url('commander') ?>" class="btn btn-gold-hover w-100 text-dark mt-2">Passer la commande</a>
                <a href="<?= site_url('panier/vider') ?>" class="btn btn-vider w-100 mt-2">Vider le Panier</a>
            </div>
        <?php else: ?>
            <div class="text-center mt-5">
                <p class="text-muted">Votre panier est vide.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
   .offcanvas {
    background-color: #fff;
    border-left: 2px solid #f1f1f1;
}

.offcanvas-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
}

.cart-items {
    max-height: 70vh;
    overflow-y: auto;
}

.cart-item {
    border-bottom: 1px solid #f1f1f1;
    padding-bottom: 10px;
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item-img {
    width: 50px;
    height: 50px;
    border-radius: 5px;
    object-fit: cover;
}

.cart-item-title {
    font-size: 1rem;
    font-weight: 600;
    color: #333;
}

.cart-item-price {
    font-size: 0.875rem;
    color: #666;
}

.cart-item-quantity {
    font-size: 0.875rem;
    color: #aaa;
}

.cart-total {
    font-size: 1rem;
    border-top: 1px solid #f1f1f1;
    padding-top: 10px;
}

.btn-vider {
    border: 1px solid #d4af37 !important;
    color: #d4af37 !important;
}

.btn-close {
    font-size: 0.8rem;
}
</style>
