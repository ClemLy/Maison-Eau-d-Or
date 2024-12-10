<div class="container">
    <h1 class="history-title mb-3" style="margin-top:100px;margin-bottom: 0; padding: 0;">Historique de vos commandes</h2>
    <?php if (!empty($orders)): ?>
        <div class="d-flex flex-wrap justify-content-center gap-4">
            <?php foreach ($orders as $order): ?>
                <div class="history-order-card flex-grow-1" style="width:100%;">
                    <h4 class="history-order-title">Commande #<?= esc($order['id_order']) ?></h4>
                    <p class="history-order-info">
                        Date : <?= date('d/m/Y H:i', strtotime($order['order_date'])) ?><br>
                        Adresse de livraison : <?= esc($order['address_street']) ?> à <?= esc($order['address_city']) ?> <?= esc($order['address_zip']) ?>, <?= esc($order['address_country']) ?>
                    </p>
                    
                    <div class="table-responsive">
                        <table class="table history-order-table">
                            <thead>
                                <tr>
                                    <th>Produits</th>
                                    <th>Prix unitaire</th>
                                    <th>Quantité</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total = 0;
                                foreach ($order['products'] as $product): 
                                    $productTotal = $product['p_price'] * $product['quantity']; 
                                    $total += $productTotal; 
                                ?>
                                    <tr>
                                        <td><?= esc($product['p_name']) ?></td>
                                        <td><?= number_format($product['p_price'], 2, ',', ' ') ?> €</td>
                                        <td><?= esc($product['quantity']) ?></td>
                                        <td><?= number_format($productTotal, 2, ',', ' ') ?> €</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <p class="history-order-total">
                        <strong>Total de la commande : <?= number_format($total, 2, ',', ' ') ?> €</strong>
                    </p>

                    <div class="text-start">
                        <a href="<?= site_url('order/pdf/' . $order['id_order']) ?>" class="btn history-order-button btn-gold-hover">
                            Afficher bon de commande
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center history-order-info">Vous n'avez pas encore passé de commande</p>
    <?php endif; ?>
</div>

<style>

</style>