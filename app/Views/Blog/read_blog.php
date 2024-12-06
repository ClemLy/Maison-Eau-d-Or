<div class="page-container">
    <h1><?= esc($article[0]['art_title']) ?></h1>

	<div class="page-content">
        <!-- Affiche l'article -->
        <div class="card">                                                                
            <div class="card-body">
                <?= $article[0]['art_text'] ?>
            </div>
        </div>                                                  
	</div>
</div>