<div class="page-container">
	<h1>Blog</h1>

	<div class="page-content">
        <!-- Affiche l'article -->
        <div class="card">                                                                
            <div class="card-body">
                <h5 class="card-title"><?= esc($article[0]['art_title']) ?></h5>
                <p class="card-text"><?= $article[0]['art_text'] ?> </p>
            </div>
        </div>                                                  
	</div>
</div>