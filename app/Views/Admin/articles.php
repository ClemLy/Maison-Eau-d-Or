<div class="admin">
    <div class="page-container">
        <div class="page-content">
            <div class="container mt-5">
                <h1 class="text-center mb-4"> Gestion du Blog </h1>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="<?= base_url('admin/blog/ajouter') ?>" class="btn btn-primary" >Ajouter un article</a>
                </div>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php if (isset($articles)): ?>
                        <?php foreach ($articles as $article): ?>
                            <tr>
                                <td>
                                    <img
                                        src="<?= isset($article['img_path']) ? $article['img_path'] : base_url('path/to/default-image.jpg') ?>"
                                        class="img-thumbnail"
                                        style="width: 100px;"
                                    >
                                </td>
                                <td><?= esc($article['art_title']) ?></td>
                                <td>
                                    <?php
                                    setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
                                    echo strftime('%d %B %Y', strtotime($article['art_date']));
                                    ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/blog/modifier/' . $article['id_art']) ?>" class="btn btn-warning" style="color=#fff!important; background:#d4af37; border: none;"> Modifier</a>
                                    <a href="<?= base_url('admin/blog/supprimer/' . $article['id_art']) ?>" class="btn btn-danger" style="border: none;" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Aucun article trouvé.</td>
                        </tr>
                    <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>