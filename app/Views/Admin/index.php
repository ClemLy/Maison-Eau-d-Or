<style>
    .list-groupe-item {
        display: flex;
        flex-direction: column;
        justify-content: center;
        border: 1px solid #e9ecef;
        border-radius: 20%;
        width: 175px;
        height: 175px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-right: 100px;
    }

    .list-groupe-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .list-groupe-item:hover a {
        color: #d4af37!important;
    }

    .list-groupe-item a {
        color: #000!important;
        text-decoration: none;
    }

    .list-groupe-item a:hover {
        text-decoration: underline;
    }

    .list-groupe-item i {
        font-size: 2rem;
        color: #000000;
    }
</style>

<div class="container mt-5">
    <h1>Fonctionnalités Administrateurs</h1>

    <?php foreach ($adminRoutes as $category => $links): ?>
        <div class="page-content">
            <h2 class="mt-4"><?= esc($category) ?></h2>
        </div>
        <ul class="list-group">
            <div class="row">
                <?php foreach ($links as $link): ?>
                    <li class="list-groupe-item logo-admin">
                        <i class="<?= $link['logo'] ?>"></i> <!-- Exemple d'icône Bootstrap -->
                        <a href="<?= base_url($link['url']) ?>" class="text-decoration-none logo-admin">
                            <?= esc($link['label']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <div class="col-md-12"><br></div>
            </div>
        </ul>
    <?php endforeach; ?>
</div>