<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?= isset($pageTitle) ? esc($pageTitle) : `Maison Eau D'Or` ?></title>
		<link href="/assets/css/style.css" rel="stylesheet" type="text/css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> <!-- Pour l'icône "..." -->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
		<script src="/assets/js/app.js"></script>
	</head>
	<body>
		<header>
			<nav>
				<ul>
					<li><a href="<?= site_url('boutique'); ?>">Boutique</a></li>
					<li><a href="<?= site_url('a-propos'); ?>">À propos</a></li>
					<li><a href="<?= site_url('blog'); ?>">Blog</a></li>
					<li><a href="<?= site_url('faq'); ?>">FAQ</a></li>
					<?php if (session()->get('isLoggedIn')): ?>
						<li><a href="<?= site_url('compte'); ?>">Mon Compte</a></li>
					<?php else: ?>
						<li><a href="<?= site_url('signin'); ?>">Se connecter</a></li>
					<?php endif; ?>
					<li><a href="<?= site_url('panier'); ?>">Panier</a></li>
				</ul>
			</nav>
		</header>