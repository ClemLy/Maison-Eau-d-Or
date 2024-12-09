<div class="page-container">
    <div class="page-content">
        <a href="<?= site_url('blog'); ?>" class="btn btn-primary mb-3" style="border: none;">
            <i class="bi bi-arrow-left"></i>
        </a>

        <h1 class="blog-title"><?= esc($article[0]['art_title']) ?></h1>

        <!-- Affichage de l'image -->
        <?php if (!empty($article[0]['img_path'])): ?>
            <div class="blog-image">
                <img src="<?= esc($article[0]['img_path']) ?>" alt="Image de l'article" class="img-fluid">
            </div>
        <?php endif; ?>

        <!-- Contenu de l'article -->
        <div class="page-content">
            <?= $article[0]['art_text'] ?>
        </div>

        <!-- Date de publication -->
        <div class="blog-date">
            <?php
                setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
                echo 'Publié le ' . strftime('%d %B %Y', strtotime($article[0]['art_date']));
            ?>
        </div>
    </div>
</div>