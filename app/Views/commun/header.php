<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= isset($pageTitle) ? esc($pageTitle) : `Maison Eau D'Or` ?></title>
	<link href="/assets/css/style.css" rel="stylesheet" type="text/css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> <!-- Pour l'icône "..." -->
	<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet"> <!-- Pour la police -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
	<script src="/assets/js/app.js"></script>
</head>

<body>
	<header>
		<nav>
			<ul>

				<div class="header-gauche">
					<li>
						<a href="<?= site_url('/'); ?>">
							<img src="https://media.tenor.com/x8v1oNUOmg4AAAAM/rickroll-roll.gif">
						</a>
					</li>
				</div>

				<div class="header-milieu">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							Boutique
						</a>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
							<?php if (isset($categories)): ?>
								<?php foreach ($categories as $category): ?>
									<li><a class="dropdown-item" href="<?= site_url("boutique/categorie/" .$category['cat_name']); ?>"><?php echo esc($category['cat_name']); ?></a></li>
								<?php endforeach; ?>
							<?php endif; ?>
						</ul>
					</li>

					<li><a href="<?= site_url('a-propos'); ?>">À propos</a></li>
					<li><a href="<?= site_url('blog'); ?>">Blog</a></li>
					<li><a href="<?= site_url('faq'); ?>">FAQ</a></li>
				</div>


				<div class="header-droite">
					<?php if (session()->get('admin')): ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="<?= site_url('admin'); ?>" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								Admin
							</a>
							<ul class="dropdown-menu" aria-labelledby="adminDropdown">
								<li><a class="dropdown-item" href="<?= site_url('admin/produits'); ?>">Gestion Produits</a></li>
								<li><a class="dropdown-item" href="<?= site_url('admin/categories'); ?>">Gestion Catégories</a></li>
								<li><a class="dropdown-item" href="<?= site_url('admin/commandes'); ?>">Gestion Commandes</a></li>
								<li><a class="dropdown-item" href="<?= site_url('admin/blog/ajouter'); ?>">Ajouter un Article</a></li>
								<li><a class="dropdown-item" href="<?= site_url('admin/a-propos/modifier'); ?>">Modifier À Propos</a></li>
								<li><a class="dropdown-item" href="<?= site_url('admin/faq/modifier'); ?>">Modifier FAQ</a></li>
							</ul>
						</li>
					<?php endif; ?>
					<?php if (session()->get('isLoggedIn')): ?>
						<li><a href="<?= site_url('account'); ?>">Mon Compte</a></li>
					<?php else: ?>
						<li><a href="<?= site_url('signin'); ?>">Se connecter</a></li>
					<?php endif; ?>
					    <li><a href="<?= site_url('panier'); ?>">Panier</a></li>
				</div>
			</ul>
		</nav>
	</header>

    <?php if (session()->has('error')): ?>
    <div class="alert alert-danger"><?= session('error') ?></div>
    <?php endif; ?>