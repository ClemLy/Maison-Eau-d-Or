<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css" integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<div class="page-container">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-4 col-sm-6">
                            <div class="search-box mb-2 me-2">
                            </div>
                        </div>

                    <hr class="mt-2">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th scope="col">Nom</th>
                                <th scope="col">Date de modification</th>
                                <th scope="col">Taille</th>
                                <th scope="col">Liaisons</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($images)): ?>
                            <?php foreach ($images as $image): ?>
                                    <tr>
                                        <td  onclick="showImageModal('<?= esc($image['img_path']) ?>')">
                                        <a href="javascript: void(0);" class="text-dark fw-medium">
                                            <i class="mdi mdi-image font-size-16 align-middle text-muted me-2"></i>
                                            <?= esc($image['img_name']) ?>
                                        </a>
                                    </td>
                                    <td  onclick="showImageModal('<?= esc($image['img_path']) ?>')"><?= !empty($image['last_modified']) ? esc($image['last_modified']) : 'Aucune date'; ?></td>
                                    <td  onclick="showImageModal('<?= esc($image['img_path']) ?>')"><?= !empty($image['file_size']) ? round($image['file_size'] / 1024, 2) . ' KB' : 'Non disponible'; ?></td>
                                    <td  onclick="showImageModal('<?= esc($image['img_path']) ?>')">
                                        <?php
                                        $relatedEntities = [];
                                        if (!empty($image['related_products'])) {
                                            foreach ($image['related_products'] as $product) {
                                                $relatedEntities[] = 'Produit: ' . esc($product['p_name']);
                                            }
                                        }
                                        if (!empty($image['related_articles'])) {
                                            foreach ($image['related_articles'] as $article) {
                                                $relatedEntities[] = 'Article: ' . esc($article['art_title']);
                                            }
                                        }
                                        echo !empty($relatedEntities) ? implode('<br>', $relatedEntities) : 'Aucune liaison';
                                        ?>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="font-size-16 text-muted" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                <i class="mdi mdi-dots-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="gestionImage/supprimer/<?= esc($image['id_img']) ?>">Supprimer</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Aucune image trouvée.</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img id="modalImage" src="" alt="Image" style="max-width: 100%; max-height: 400px;">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->

                </div>
            </div>
        </div>
    </div>
</div>




<style>
body{margin-top:20px;
background-color: #f1f3f7;
}
.search-box .form-control {
    border-radius: 10px;
    padding-left: 40px
}

.search-box .search-icon {
    position: absolute;
    left: 13px;
    top: 50%;
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);
    fill: #545965;
    width: 16px;
    height: 16px
}
.card {
    margin-bottom: 24px;
    -webkit-box-shadow: 0 2px 3px #e4e8f0;
    box-shadow: 0 2px 3px #e4e8f0;
}
.card {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid #eff0f2;
    border-radius: 1rem;
}
.me-3 {
    margin-right: 1rem!important;
}

.font-size-24 {
    font-size: 24px!important;
}
.avatar-title {
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    background-color: #3b76e1;
    color: #fff;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    font-weight: 500;
    height: 100%;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    width: 100%;
}

.bg-soft-info {
    background-color: rgba(87,201,235,.25)!important;
}

.bg-soft-primary {
    background-color: rgba(59,118,225,.25)!important;
}

.avatar-xs {
    height: 1rem;
    width: 1rem
}

.avatar-sm {
    height: 2rem;
    width: 2rem
}

.avatar {
    height: 3rem;
    width: 3rem
}

.avatar-md {
    height: 4rem;
    width: 4rem
}

.avatar-lg {
    height: 5rem;
    width: 5rem
}

.avatar-xl {
    height: 6rem;
    width: 6rem
}

.avatar-title {
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    background-color: #3b76e1;
    color: #fff;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    font-weight: 500;
    height: 100%;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    width: 100%
}

.avatar-group {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    padding-left: 8px
}

.avatar-group .avatar-group-item {
    margin-left: -8px;
    border: 2px solid #fff;
    border-radius: 50%;
    -webkit-transition: all .2s;
    transition: all .2s
}

.avatar-group .avatar-group-item:hover {
    position: relative;
    -webkit-transform: translateY(-2px);
    transform: translateY(-2px)
}

.fw-medium {
    font-weight: 500;
}

a {
    text-decoration: none!important;
}
</style>

<script>
    function showImageModal(imagePath) {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imagePath; // Définir l'image dans la modale
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'), {
            keyboard: true
        });
        imageModal.show(); // Afficher la modale
    }
</script>