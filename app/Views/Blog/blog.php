<div class="container">
	<h1>Blog</h1>
	<p>Découvrez nos derniers articles sur l'univers des parfums.</p>

	<div class="row">
		<?php if (isset($articles)): ?>
			<?php foreach ($articles as $article): ?>
				<div class="col-md-4 mb-4">
					<div class="card h-100">
						<img src="<?= esc($article['img_path']) ?>" class="card-img-top" alt="<?= esc($article['art_title']) ?>">
						<div class="card-body">
							<h5 class="card-title"><?= esc($article['art_title']) ?></h5>
							<p class="card-text"><?= substr(strip_tags($article['art_text']), 0, 150) ?>...</p>
							<a href="<?= site_url('blog/' . $article['id_art']) ?>" class="btn btn-primary">Lire la suite</a>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>