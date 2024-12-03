<div class="container mt-5">
    <h1>Fonctionnalités Administratives</h1>
    <p>Voici les fonctionnalités disponibles pour l'administrateur :</p>

    <?php foreach ($adminRoutes as $category => $links): ?>
        <h3 class="mt-4"><?= esc($category) ?></h3>
        <ul class="list-group">
            <?php foreach ($links as $link): ?>
                <li class="list-group-item">
                    <a href="<?= base_url($link['url']) ?>" class="text-decoration-none">
                        <?= esc($link['label']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
</div>