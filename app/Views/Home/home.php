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
            <button type="button" data-bs-target="#carouselImgAcceuil" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselImgAcceuil" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselImgAcceuil" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://www.beautysuccess.fr/media/catalog/product/cache/02280392440d22bedc5c4ce4592badc4/4/3/4371782058-guerlain-l-homme-ideal-100ml-visuel_4.webp" class="d-block w-100" alt="Image 1">
            </div>
            <div class="carousel-item">
                <img src="https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/3f1302d3-be16-4ce7-b177-b4421213d687.jpeg" class="d-block w-100">
            </div>
            <div class="carousel-item">
                <img src="https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/77f77e8d-ceb1-4544-bcdd-b216e52c0d1a.jpeg" class="d-block w-100">
            </div>
        </div>
    </div>


    <div class="categories-vedette d-flex justify-content-around py-4">
        <?php
        if (isset($categories) && !empty($categories)) {
            foreach ($categories as $categ) {
                // Vérifier si la catégorie est sélectionnée
                $activeClass = ($categ['id_cat'] == $selectedCategoryId) ? 'active' : '';
                echo '<h3><a href="?category_id=' . $categ['id_cat'] . '" class="' . $activeClass . '">' . $categ['cat_name'] . '</a></h3>';
            }
        }
        ?>
    </div>

    <hr class="trait-doree">

    <!-- Carrousel Produits -->
    <?php if (isset($selectedProducts) && !empty($selectedProducts)): ?>
        <div id="carouselProduits" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php
                $activeClass = 'active';
                foreach ($selectedProducts as $index => $product) {
                    echo '<button type="button" data-bs-target="#carouselProduits" data-bs-slide-to="' . $index . '" class="' . $activeClass . '" aria-label="Slide ' . ($index + 1) . '"></button>';
                    $activeClass = ''; // Seul le premier bouton doit être actif
                }
                ?>
            </div>

            <div class="carousel-inner">
                <?php
                $activeClass = 'active'; // Le premier produit sera actif
                foreach ($selectedProducts as $product) {
                    echo '<div class="carousel-item ' . $activeClass . '">';
                    echo '<img src="' . esc($product['img_path']) . '" class="d-block w-100" alt="' . esc($product['p_name']) . '">';
                    echo '<div class="carousel-caption d-none d-md-block">';
                    echo '<h5>' . esc($product['p_name']) . '</h5>';
                    echo '<p>' . esc($product['description']) . '</p>';
                    echo '</div>';
                    echo '</div>';
                    $activeClass = ''; // Seul le premier produit doit être actif
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



    <div class="text-center mt-4">
        <a href="#PRODUITS">
            <p>Voir tous les produits</p>
        </a>
    </div>

    <!-- Section Produit Vedette -->

    <?php if (isset($starProduct) && !empty($starProduct)) { ?>
        <div class="text-center mt-5">
            <h2 class="texte-doree">Notre produit vedette</h2>
            <hr class="trait-doree">

            <div class="row align-items-center produit-vedette mt-4">
                <!-- Colonne de l'image -->
                <div class="col-md-6 d-flex justify-content-center div-img-vedette">
                    <img src="<?= esc($starProduct['img_path']); ?>" class="img-vedette" alt="Produit Vedette">
                </div>

                <!-- Colonne texte -->
                <div class="col-md-6 text-start vedette-texte ps-4">
                    <h3 class="fw-bold"><?= esc($starProduct['p_name']) ?></h3>
                    <p><?= esc($starProduct['description']) ?></p>
                    <p class="texte-doree fs-4 fw-bold"><?= esc($starProduct['p_price']) . '€' ?></p>

                    <form action="<?= site_url('/panier/ajouter') ?>" method="post">

                        <div class="d-flex align-items-center mt-3">
                            <div class="input-group" style="width: 150px;">
                                <button class="btn btn-outline-primary" type="button" id="decrement" onclick="updateValue(-1)">-</button>
                                <input type="number" class="form-control text-center" id="numberInput" value="0" min="0" max="10" readonly>
                                <button class="btn btn-outline-primary" type="button" id="increment" onclick="updateValue(1)">+</button>
                            </div>
                        </div>

                        <button class="btn btn-black mt-3">Ajouter au panier <i class="fas fa-shopping-cart"></i></button>

                    </form>

                </div>
            </div>
        </div>
    <?php } else {
        echo '<p class="alert alert-info">Aucun article en vedette</p>';
    }
    ?>
</div>