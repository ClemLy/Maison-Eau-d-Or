<h1><?= $category['cat_name'] ?></h1>

<?php if (!empty($products)): ?>
    <div class="product-list">
        <?php foreach ($products as $product): ?>
            <div class="product-item">
                <img src="<?=$product['img_path'] ?>" alt="<?= esc($product['p_name']) ?>" width="200px;height:200px";>
                <h2><?= esc($product['p_name']) ?></h2>
                <p><?= esc($product['description']) ?></p>
                <p><strong>Prix:</strong> <?= esc($product['p_price']) ?> €</p>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>Aucun produit trouvé dans cette catégorie.</p>
<?php endif; ?>
