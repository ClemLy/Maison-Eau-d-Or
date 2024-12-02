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
        for ($i = 1; $i < 5; $i++) {
            echo '<h3>Catégorie ' . $i . '</h3>';
        }
        ?>
    </div>

    <hr class="trait-doree">

    <!-- Carrousel Produits -->
    <div id="carouselPrdouitsAcceuil" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselPrdouitsAcceuil" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselPrdouitsAcceuil" data-bs-slide-to="1" aria-label="Slide 2"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/3f1302d3-be16-4ce7-b177-b4421213d687.jpeg">
                <img src="https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/7f0da4d2-b42d-4b0b-ac2f-a2bd78422b0b.jpeg">
                <img src="https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/e5972641-36bf-4b0a-aab4-62b7e5d5d17e.jpeg">
            </div>
            <div class="carousel-item">
                <img src="https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/1200b8b3-272f-40e3-bc98-c928547e2ed1.jpeg">
                <img src="https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/9a09a44c-aa30-41e2-a9f7-ad6ad41a66a3.jpeg">
                <img src="https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/4f538ea5-703c-425d-88bc-3b01a935f18a.jpeg">
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselPrdouitsAcceuil" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Précédent</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselPrdouitsAcceuil" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Suivant</span>
        </button>
    </div>

    <!-- Lien pour voir tous les produits -->
    <div class="text-center mt-4">
        <a href="#PRODUITS">
            <p>Voir tous les produits</p>
        </a>
    </div>

    <!-- Section Produit Vedette -->
    <div class="text-center mt-5">
        <h2 class="texte-doree">Notre produit vedette</h2>
        <hr class="trait-doree">

        <img src="https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/1200b8b3-272f-40e3-bc98-c928547e2ed1.jpeg">

        <p>GOKUUU</p>
        <p>Lorem ipsum dolor est optio officiis vitae nisi ex llitia. Pariatur totam vitae repellendus assumenda possimus quae voluptatem aut.eque exercitationem culpa!</p>
        <p class="texte-doree">10€</p>

        <button class="btn btn-black">Ajouter au panier<i class="fas fa-shopping-cart"></i></button>

        <!-- Contrôles de quantité -->
        <div class="container mt-5">
            <div class="d-flex justify-content-center">
                <div class="input-group" style="width: 150px;">
                    <button class="btn btn-outline-primary" type="button" id="decrement" onclick="updateValue(-1)">-</button>
                    <input type="number" class="form-control text-center" id="numberInput" value="0" min="0" max="10" readonly>
                    <button class="btn btn-outline-primary" type="button" id="increment" onclick="updateValue(1)">+</button>
                </div>
            </div>
        </div>
    </div>
</div>
