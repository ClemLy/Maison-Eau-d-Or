<div class="page-container">
    <div class="container my-5">
        <h1 class="mb-4">Votre Panier</h1>


        <?php if (session()->get('danger')): ?>

            <div class="alert alert-danger">
                <?= session()->get('danger') ?>
            </div>

        <?php endif; ?>

        <div class="page-content" >
            <?php if (!empty($cartItems)): ?>
                <table class="table table-bordered  ">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Produit</th>
                            <th>Prix Unitaire</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td>
                                    <img src="<?= esc($item['img_path']) ?>" alt="Produit" width="100">
                                </td>
                                <td><?= esc($item['p_name']) ?></td>
                                <td><?= number_format($item['p_price'], 2, ',', ' ') ?> €</td>
                                <td>
                                <div class="input-group" style="width: 150px;">
                                    <button class="btn btn-outline-dark btn-sm" type="button" onclick="updateValueSideMenu(this, -1, <?= $item['id_prod']?>)">-</button>
                                    <input type="number" class="form-control text-center" name="quantity" value="<?= $item['quantity']?>" min="1" max="10" readonly>
                                    <button class="btn btn-outline-dark btn-sm" type="button" onclick="updateValueSideMenu(this, 1, <?= $item['id_prod']?>)">+</button>
                                </div>
                                    <!-- <form action="<?= site_url('panier/modifier/' . $item['id_prod']) ?>" method="post" class="d-inline">
                                        <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="99" class="form-control w-50 d-inline">
                                        <button type="submit" class="btn btn-sm btn-primary">Modifier</button>
                                    </form> -->
                                </td>
                                <td><?= number_format($item['p_price'] * $item['quantity'], 2, ',', ' ') ?> €</td>
                                <td>
                                    <a href="<?= site_url('panier/supprimer/' . $item['id_prod']) ?>" class="btn btn-sm btn-danger">
                                        Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total :</strong></td>
                            <td colspan="2">
                                <?= number_format(array_reduce($cartItems, function ($sum, $item) {
                                    return $sum + $item['p_price'] * $item['quantity'];
                                }, 0), 2, ',', ' ') ?> €
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="text-end">
                    <a href="<?= site_url('panier/vider') ?>" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Vider le Panier
                    </a>
                    <a href="<?= site_url('commander') ?>" class="btn btn-success">Passer la Commande</a>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Votre panier est vide.</div>
            <?php endif; ?>
        </div>
    </div>
</div>



