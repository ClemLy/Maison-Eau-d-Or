<div class="container mt-5">
    <h2>Historique de vos commandes</h2>
    <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $order): ?>
            <div class="order-card mb-4 p-3 border">
                <h4>Commande #<?= esc($order['id_order']) ?></h4>
                <p>Date : <?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></p>
                <p>Adresse de livraison : <?= esc($order['address_street']) ?> à <?= esc($order['address_city']) ?>  <?= esc($order['address_zip']) ?>, <?= esc($order['address_country']) ?></p>
                
                <table class="table">
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
                <p class="text-end">
                    <strong>Total de la commande : <?= number_format($total, 2, ',', ' ') ?> €</strong>
                </p>


                <div class="text-start">
                    <a href="<?= site_url('order/pdf/' . $order['id_order']) ?>" class="btn btn-primary">
                        Afficher bon de commande
                    </a>
                </div>
            </div>
            
        <?php endforeach; ?>
    <?php else: ?>
        <p>Vous n'avez pas encore passé de commande</p>
    <?php endif; ?>
</div>
