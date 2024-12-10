<div class="container my-5">
    <div class="menu-categories_list-categories mb-4 text-center">
        <a href="<?= site_url('boutique') ?>" class="btn btn-list-category <?= (current_url() == site_url('boutique')) ? 'active' : '' ?>">Toutes les catégories</a>

        <?php foreach ($categories as $category): ?>
            <a href="<?= site_url('boutique/categorie/' . $category['cat_name']) ?>" class="btn btn-list-category <?= (current_url() == site_url('boutique/categorie/' . $category['cat_name'])) ? 'active' : '' ?>">
                <?= esc($category['cat_name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<style>
    .menu-categories_list-categories {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 30px;
    }


    .btn-list-category {
        font-size: 1rem;
        padding: 12px 20px;
        background-color: #fff;
        color: #333;
        border: 2px solid #ddd;
        border-radius: 25px;
        text-transform: uppercase;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .btn-list-category:hover,
    .btn-list-category.active {
        background-color: #d4af37;
        border-color: #d4af37;
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-list-category:focus {
        outline: none;
    }

    .btn-list-category:hover {
        color: #d4af37;
    }
</style>