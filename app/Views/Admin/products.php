<div class="admin">
  <div class="page-container">
    <div class="container mt-5">
      <h1 class="text-center mb-4">Liste des produits</h1>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="<?= base_url('admin/produit/ajouter') ?>" 
           class="btn btn-primary" 
           style="background-color: #d4af37; border-color: #d4af37; color: #fff;">
           Ajouter un produit
        </a>

      </div>

      <table class="table table-striped">
        <thead>
          <tr style="background-color: #f7f7f7; color: #333;">
            <th>Image</th>
            <th>Nom</th>
            <th>Prix</th>
            <th>Catégories</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (isset($products)): ?>
            <?php foreach ($products as $product): ?>
              <tr>
                <td>
                  <img src="<?= isset($product['images'][0]['img_path']) 
                                ? $product['images'][0]['img_path'] 
                                : base_url('path/to/default-image.jpg') ?>" 
                       class="img-thumbnail" 
                       style="width: 100px; border: 1px solid #d4af37;">
                </td>
                <td><?= esc($product['p_name']) ?></td>
                <td><?= esc($product['p_price']) ?> €</td>
                <td>

                  <?php foreach ($product['categories'] as $category): ?>
                    <?= esc($category['cat_name']) ?><br>
                  <?php endforeach; ?>
                </td>
                <td>
                  <a href="<?= base_url('admin/produit/modifier/' . $product['id_prod']) ?>" 
                     class="btn btn-warning" 
                     style="background:#d4af37; border-color: #d4af37; color: #fff;">
                     Modifier
                  </a>
                  <a href="<?= base_url('admin/produit/supprimer/' . $product['id_prod']) ?>" 
                     class="btn btn-danger" 
                     onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                     Supprimer
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center" style="color: #d4af37;">
                Aucun produit trouvé.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
