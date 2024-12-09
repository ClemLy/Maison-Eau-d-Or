<div class="page-container">
	<h1>Blog</h1>
	<div class="page-content">
		<div class="row">
			<?php if (isset($articles)): ?>
				<?php foreach ($articles as $article): ?>
					<div class="col-md-4 mb-4">
						<div class="card h-100">
							<img src="<?= esc($article['img_path']) ?>" class="card-img-top" alt="<?= esc($article['art_title']) ?>">
							<div class="card-body">
								<h5 class="card-title"><?= esc($article['art_title']) ?></h5>
								<p class="text-muted">
									<?php
										setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
										echo 'Publié le ' . strftime('%d %B %Y', strtotime($article['art_date']));
									?>
								</p>
								<p class="card-text"><?= substr(strip_tags($article['art_text']), 0, 150) ?>...<a href="<?= site_url('blog/' . $article['id_art']) ?>">Lire la suite</a></p>
								
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</div>