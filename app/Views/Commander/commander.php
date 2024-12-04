<div class="container my-5">
    <h2 class="mb-4">Passer la Commande</h2>

    <form action="<?= site_url('commander/valider_commande') ?>" method="POST">
        <div class="row">
            <div class="col-md-6">
                <label for="first_name" class="form-label">Prénom</label>
                <input type="text" name="first_name" id="first_name" class="form-control" value="<?= esc($user['first_name']) ?>" required>
            </div>
            <div class="col-md-6">
                <label for="last_name" class="form-label">Nom</label>
                <input type="text" name="last_name" id="last_name" class="form-control" value="<?= esc($user['last_name']) ?>" required>
            </div>
        </div>

        <div class="mt-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= esc($user['email']) ?>" placeholder="Email" required>
        </div>

        <div class="mt-3">
            <label for="phone_number" class="form-label">Numéro de téléphone</label>
            <input type="tel" name="phone_number" id="phone_number" class="form-control" value="<?= esc($user['phone_number']) ?>" placeholder="Numéro de téléphone" required>
        </div>

        <div class="mt-3">
            <label for="address" class="form-label">Adresse de livraison</label>
            <textarea name="address" id="address" class="form-control" rows="4" placeholder="Adresse de livraison" required></textarea>
        </div>

        <div class="mt-3">
            <label for="total" class="form-label">Total de la commande</label>
            <input type="text" class="form-control" id="total" value="<?= number_format($total, 2, ',', ' ') ?> €" readonly>
        </div>

        <div class="text-end mt-3">
            <button type="submit" class="btn btn-success">Valider la commande</button>
        </div>
    </form>
</div>
