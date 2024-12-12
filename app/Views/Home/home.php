<style>
    .carousel-inner .carousel-item img {
        width: 100%;
        /* L'image prend toute la largeur du carrousel */
        height: 400px;
        /* Hauteur fixe pour les images du carrousel */
        object-fit: cover;
        /* L'image se découpe pour remplir la zone sans déformer son aspect */
        object-position: center;
        /* Centre l'image si elle est recadrée */
    }

    /* Optionnel : Pour ajuster le carrousel s'il y a des problèmes de marges ou de dépassement */
    .carousel-inner {
        overflow: hidden;
        /* Masque tout excédent d'image qui dépasse */
    }
</style>


<div class="container-fluid">
    <!-- Carrousel Image d'accueil -->
    <div id="carouselImgAcceuil" class="carousel slide mx-auto" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php foreach ($carouselImages as $index => $image): ?>
                <button type="button" data-bs-target="#carouselImgAcceuil" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>" aria-label="Slide <?= $index + 1 ?>"></button>
            <?php endforeach; ?>
        </div>

        <div class="carousel-inner">
            <?php foreach ($carouselImages as $index => $image): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <a href="<?= esc($image['link_car']) ?>">
                        <img src="<?= esc($image['img_path']) ?>" class="d-block w-100" alt="Image <?= $index + 1 ?>">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>


    <div class="categories-vedette d-flex justify-content-around pb-0 mt-5">
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $categ): ?>
                <h3>
                    <a href="?category_id=<?= esc($categ['id_cat']) ?>" 
                    class="<?= ($categ['id_cat'] == $selectedCategoryId) ? 'active' : '' ?>">
                    <?= esc($categ['cat_name']) ?>
                    </a>
                </h3>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune catégorie disponible pour le moment.</p>
        <?php endif; ?>
    </div>

    <hr class="mt-0">

    <!-- Carrousel Produits -->
    <div class="col-md-3">
        <?php if (isset($selectedProducts) && !empty($selectedProducts)): ?>
            <div id="carouselProduits" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <?php
                        $activeClass = 'active';
                        foreach ($selectedProducts as $index => $product)
                        {
                            echo '<button type="button" data-bs-target="#carouselProduits" data-bs-slide-to="' . $index . '" class="' . $activeClass . '" aria-label="Slide ' . ($index + 1) . '"></button>';
                            $activeClass = ''; // Seul le premier bouton doit être actif
                        }
                    ?>
                </div>

                <div class="carousel-inner">
                    <?php
                        $activeClass = 'active'; // Le premier produit sera actif

                  
              
                        foreach ($selectedProducts as $product) {

                            // Récupérer la chaîne des images
                            $imgPathsString = $product['img_paths'];
                            
                            // Supprimer les accolades et séparer les images par des virgules
                            $imgPathsArray = explode(',', trim($imgPathsString, '{}'));
                            
                            // Récupérer la première image
                            $firstImage = $imgPathsArray[0];
                            
                            // Afficher l'élément carousel avec la première image
                            echo '<div class="carousel-item ' . $activeClass . '">';
                            echo '<img src="' . esc($firstImage) . '" class="d-block w-100 img-fluid" alt="' . esc($product['p_name']) . '">';
                            echo '<div class="product-banner">'; // Bandeau sous l'image
                            echo '<h5 class="product-name">' . esc($product['p_name']) . '</h5>';
                            echo '<p class="product-price">' . number_format($product['p_price'], 2, ',', ' ') . ' €</p>';
                            echo '</div>';
                            echo '</div>';
                        
                            // Après le premier produit, la classe 'active' sera supprimée
                            $activeClass = '';
                        }
                    ?>
                </div>

                <!-- Contrôles du carrousel -->
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselProduits" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Précédent</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselProduits" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Suivant</span>
                </button>
            </div>
        <?php else: ?>
            <p>Aucun produit disponible dans cette catégorie.</p>
        <?php endif; ?>
    </div>



    <div class="text-center mt-4">
        <a href="<?= site_url('boutique');?>">
            <p>Voir tous les produits</p>
        </a>
    </div>

    <!-- Section Produit Vedette -->

    <?php if (isset($starProduct) && !empty($starProduct)) { ?>
        <h2 class="texte-doree mt-5">Notre produit vedette</h2>
        <hr>
        <div class="text-center mt-0">
            <div class="row align-items-center produit-vedette mt-0">
                <!-- Colonne de l'image -->
                <div class="col-md-6 d-flex justify-content-center div-img-vedette">
                <img src="<?= esc($starProduct['img_path']); ?>" class="product-image" alt="Produit Vedette">
                </div>

                <!-- Colonne texte -->
                <div class="col-md-6 text-start vedette-texte ps-4">
                    <h3 class="fw-bold"><?= esc($starProduct['p_name']) ?></h3>
                    <p><?= esc($starProduct['description']) ?></p>
                    <p class="texte-doree fs-4 fw-bold"><?= esc($starProduct['p_price']) . '€' ?></p>

                    <form class="d-flex flex-row justify-content-between flex-space-between add-to-cart-form" action="<?= site_url('/panier/ajouter/' . $starProduct['id_prod']) ?>" method="post">
                        <div class="d-flex align-items-center mt-3">
                            <div class="input-group" style="width: 150px;">
                                <button class="btn btn-outline-dark btn-sm" type="button" id="decrement" onclick="updateValue(-1)">-</button>
                                <input type="number" class="form-control text-center" id="numberInput" name="quantity" value="1" min="1" max="99" readonly>
                                <button class="btn btn-outline-dark btn-sm" type="button" id="increment" onclick="updateValue(1)">+</button>
                            </div>
                        </div>

                        <button class="btn btn-black mt-3 btn-gold-hover" data-bs-toggle="offcanvas" data-bs-target="#panier_sideMenu"  style="color:#FFF!important;">Ajouter au panier <i class="fas fa-shopping-cart"></i></button>
                    </form>

                </div>
            </div>
        </div>

    <?php } else {
        echo '<p class="alert alert-info">Aucun article en vedette</p>';
    }
    ?>

    <br>

    <script>
        function updateValue(step) {
            const input = document.getElementById('numberInput');
            let value = parseInt(input.value);
            const newValue = value + step;
            if (newValue >= parseInt(input.min) && newValue <= parseInt(input.max)) {
                input.value = newValue;
            }
        }
    </script>

    <script src="https://static.elfsight.com/platform/platform.js" async></script>
    <div class="elfsight-app-5626288e-3079-4c8b-bf5b-af021b2da7f7" data-elfsight-app-lazy></div>
</div>

<?php if (!session()->get('newsletter')): ?>
    <div class="newsletter-section d-flex align-items-center justify-content-between p-4 my-5">
        <div class="newsletter-title">
            <h3>Newsletter <br> Maison Eau d'Or</h3>
        </div>
        <div class="newsletter-text">
            <p>Inscrivez-vous à notre newsletter pour recevoir nos dernières actualités</p>
        </div>
        <div class="newsletter-button">
            <form action="<?= site_url('newsletter/subscribe'); ?>" method="post">
                <button type="submit" class="btn btn-primary">Inscrivez-vous !</button>
            </form>
        </div>
    </div>
<?php endif; ?>