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

				<div class="header-gauche">
					<li>
						<a href="<?= site_url('/'); ?>">
							<img src="https://scontent-cdg4-1.xx.fbcdn.net/v/t39.30808-6/304807388_470884035036265_5060699602632772978_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=mn6vk33bZsQQ7kNvgGvoQ4e&_nc_zt=23&_nc_ht=scontent-cdg4-1.xx&_nc_gid=AwwRCg9YtF_gTG9rGVCUFGk&oh=00_AYB60jlbrQ20saAozc4VKSO-hnpiH7z6tUFRjB74A1qrYA&oe=6753AA50">
						</a>
					</li>
				</div>

				<div class="header-milieu">
					<li><a href="<?= site_url('boutique'); ?>">Boutique</a></li>
					<li><a href="<?= site_url('a-propos'); ?>">À propos</a></li>
					<li><a href="<?= site_url('blog'); ?>">Blog</a></li>
					<li><a href="<?= site_url('faq'); ?>">FAQ</a></li>
				</div>

				<div class="header-droite">
					<?php if (session()->get('isLoggedIn')): ?>
						<li><a href="<?= site_url('compte'); ?>">Mon Compte</a></li>
					<?php else: ?>
						<li><a href="<?= site_url('signin'); ?>">Se connecter</a></li>
					<?php endif; ?>
					<li><a href="<?= site_url('panier'); ?>">Panier</a></li>
				</div>
			</ul>
		</nav>
	</header>