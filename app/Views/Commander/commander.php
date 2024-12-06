<div class="container my-5">
    <h2 class="mb-4 text-center">Passer la Commande</h2>

    <form action="<?= site_url('commander/valider_commande') ?>" method="POST">
        <div class="row">
            <div class="col-md-6">
                <label for="first_name" class="form-label">Prénom (*)</label>
                <input type="text" name="first_name" id="first_name" class="form-control" value="<?= esc($user['first_name']) ?>" required>
            </div>
            <div class="col-md-6">
                <label for="last_name" class="form-label">Nom (*)</label>
                <input type="text" name="last_name" id="last_name" class="form-control" value="<?= esc($user['last_name']) ?>" required>
            </div>
        </div>

        <div class="mt-3">
            <label for="email" class="form-label">E-mail (*)</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= esc($user['email']) ?>" placeholder="Email" required>
        </div>

        <div class="mt-3">
            <label for="phone_number" class="form-label">Numéro de téléphone (*)</label>
            <input type="tel" name="phone_number" id="phone_number" class="form-control" value="<?= esc($user['phone_number']) ?>" placeholder="Numéro de téléphone" required>
        </div>

        <hr class="my-4">

        <h4>Adresse de livraison</h4>
        <div class="row">
            <div class="col-md-6">
                <label for="address_street" class="form-label">Rue (*)</label>
                <input type="text" name="address_street" id="address_street" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="address_city" class="form-label">Ville (*)</label>
                <input type="text" name="address_city" id="address_city" class="form-control" required>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="address_zip" class="form-label">Code postal (*)</label>
                <input type="text" name="address_zip" id="address_zip" class="form-control" required maxlength="5">
            </div>
            <div class="col-md-6">
                <label for="address_country" class="form-label">Pays (*)</label>
                <input type="text" name="address_country" id="address_country" class="form-control" required>
            </div>
        </div>

        <hr class="my-4">

        <div class="mt-3">
            <label for="total" class="form-label">Total de la commande</label>
            <input type="text" class="form-control" id="total" value="<?= number_format($total, 2, ',', ' ') ?> €" readonly>
        </div>

        <div class="text-end mt-3">
            <p class="text-muted">(*) Champs obligatoires</p>
        </div>

        <div class="text-end mt-3">
            <button type="submit" class="btn btn-success">Valider la commande</button>
        </div>
    </form>
</div>