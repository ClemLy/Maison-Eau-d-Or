<script>
    var panierUrl = "<?= site_url('panier') ?>";
    var commanderUrl = "<?= site_url('commander') ?>";
    var viderPanierUrl = "<?= site_url('panier/vider') ?>";
</script>


<button id="panierBtn" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#panier_sideMenu" aria-controls="panier_sideMenu">
    Panier
</button>

<div class="offcanvas offcanvas-end" tabindex="-1" id="panier_sideMenu" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Votre Panier</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="cart-items"></div>
        <div class="cart-total mt-4"></div>

        <div class="empty-cart-message text-center mt-5 d-none">
            <p class="text-muted">Votre panier est vide.</p>
        </div>

        <div class="cart-buttons d-none">
            <a href="<?= site_url('panier') ?>" class="btn btn-gold-hover w-100 text-dark">Afficher les détails</a>
            <a href="<?= site_url('commander') ?>" class="btn btn-gold-hover w-100 text-dark mt-2">Passer la commande</a>
            <a href="<?= site_url('panier/vider') ?>" class="btn btn-vider w-100 mt-2">Vider le Panier</a>
        </div>
    </div>
</div>

<style>
    
    .cart-item {
        transition: all 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
        /* Plus fluide avec un easing */
        opacity: 1;
        transform: scale(1) translateY(0);
        height: auto;
        overflow: hidden;
    }

    .cart-item.removing {
        opacity: 0;
        transform: scale(0.95) translateY(-20px);
        /* Réduction de taille et déplacement vers le haut */
        height: 0;
        margin: 0;
        padding: 0;
    }

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





<?php if (null !== session()->get('isLoggedIn')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const panierSideMenu = document.getElementById('panier_sideMenu');
            console.log("Fdp");
            if (panierSideMenu) {
                // Initialiser l'Offcanvas avec le backdrop activé
                let offcanvas = new bootstrap.Offcanvas(panierSideMenu, {
                    backdrop: true,  // L'écran gris sera visible derrière le panier
                    keyboard: true    // Permet de fermer l'Offcanvas avec la touche Échap
                });

                // Si vous avez un bouton pour ouvrir l'Offcanvas
                const panierButton = document.getElementById('panierBtn');
                if (panierButton) {
                    panierButton.addEventListener('click', function () {
                        offcanvas.show();
                    });
                }

                // Fermer l'Offcanvas lorsque le backdrop est cliqué (clic à l'extérieur)
                panierSideMenu.addEventListener('hidden.bs.offcanvas', function () {
                    // Réinitialiser l'état après la fermeture
                    document.body.style.overflow = '';  // Réinitialise le défilement
                    // Retirer le backdrop lorsque l'Offcanvas est fermé
                    const backdrop = document.querySelector('.offcanvas-backdrop');
                    if (backdrop) {
                        backdrop.remove();  // Supprime l'arrière-plan gris
                    }
                });

                // Facultatif : si vous voulez gérer les comportements au clic à l'extérieur
                // Quand le backdrop est cliqué, on cache l'Offcanvas
                const backdrop = document.querySelector('.offcanvas-backdrop');
                if (backdrop) {
                    backdrop.addEventListener('click', function () {
                        offcanvas.hide(); // Cache le panier si l'on clique à l'extérieur
                    });
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.add-to-cart-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateCartDisplay();
                            let offcanvas = new bootstrap.Offcanvas(document.getElementById('panier_sideMenu'));
                            offcanvas.show();
                        } else {
                            alert('Erreur lors de l\'ajout au panier');
                        }
                    });
                });
            });

        
        });
    </script>
<?php endif; ?>
