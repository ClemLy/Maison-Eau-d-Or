<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= isset($pageTitle) ? esc($pageTitle) . ' | Maison Eau d\'Or' : `Maison Eau D'Or` ?></title>
	<link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">
	<link href="/assets/css/style.css" rel="stylesheet" type="text/css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> <!-- Pour l'icône "..." -->
	<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet"> <!-- Pour la police -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
	<script src="/assets/js/app.js"></script>
</head>
<?php

use App\Models\CategoryModel;
use App\Models\CartModel;
$cartModel = new CartModel();
$categoryModel = new CategoryModel();

$categories = $categoryModel->findAll();



$id_user = session()->get('id_user');

		
$cartItems = $cartModel
		->select('cart.id_prod, cart.quantity, product.p_name, product.p_price, image.img_path')
		->join('product', 'cart.id_prod = product.id_prod')
		->join('product_image', 'product.id_prod = product_image.id_prod')
		->join('image', 'product_image.id_img = image.id_img')
		->where('cart.id_user', $id_user)
		->findAll();
?>
<body>
	<header>
		<nav>
			<ul>

				<div class="header-gauche">
					<li>
						<a href="<?= site_url('/'); ?>">
							<img src="/assets/img/logo-accueil.png" style="width: 100px; height: 50px;">
						</a>
					</li>
				</div>

				<div class="header-milieu">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" onclick="window.location.href='<?= site_url('boutique'); ?>'" href="<?= site_url('boutique');?>" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						Boutique
						</a>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
							<li>
								<?php if (isset($categories)): ?>
									<?php foreach ($categories as $category): ?>
										<a class="dropdown-item" href="<?= site_url("boutique/categorie/" .$category['cat_name']); ?>"><?php echo esc($category['cat_name']); ?></a>
									<?php endforeach; ?>
								<?php endif; ?>
							</li>
						</ul>
					</li>

					<li><a href="<?= site_url('a-propos'); ?>">À propos</a></li>
					<li><a href="<?= site_url('blog'); ?>">Blog</a></li>
					<li><a href="<?= site_url('faq'); ?>">FAQ</a></li>
				</div>


				<div class="header-droite">
					<?php if (session()->get('admin')): ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" onclick="window.location.href='<?= site_url('admin'); ?>'" href="<?= site_url('admin'); ?>" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								Admin
							</a>
							<ul class="dropdown-menu" aria-labelledby="adminDropdown">
								<li>
									<a class="dropdown-item" href="<?= site_url('admin/produits'); ?>">Gestion Produits	</a>
									<a class="dropdown-item" href="<?= site_url('admin/carrousel'); ?>">Gestion Carrousel</a>
									<a class="dropdown-item" href="<?= site_url('admin/commandes'); ?>">Gestion Commandes</a>
									<a class="dropdown-item" href="<?= site_url('admin/blog'); ?>">Gestion Blog</a>
									<a class="dropdown-item" href="<?= site_url('admin/a-propos/modifier'); ?>">Modifier À Propos</a>
									<a class="dropdown-item" href="<?= site_url('admin/faq/modifier'); ?>">Modifier FAQ</a>
									<a class="dropdown-item" href="<?= site_url('admin/gestionImage'); ?>">Gestion Images</a>
								</li>
							</ul>
						</li>
					<?php endif; ?>

					<?php if (session()->get('isLoggedIn')): ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" onclick="window.location.href='<?= site_url('account'); ?>'" href="<?= site_url('account'); ?>" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								Mon Compte
							</a>
							<ul class="dropdown-menu" aria-labelledby="accountDropdown">
								<li>
									<a class="dropdown-item" href="<?= site_url('account/historique'); ?>">Historique</a>
									<a class="dropdown-item" href="<?= site_url('account/update'); ?>">Modifier</a></li>
									<a class="dropdown-item" href="<?= site_url('logout'); ?>">Se Déconnecter</a>						
								</li>
							</ul>
						</li>
					<?php else: ?>
						<li><a href="<?= site_url('signin'); ?>">Se connecter</a></li>
					<?php endif; ?>
					<?php $totalItems = array_sum(array_column($cartItems, 'quantity')); ?>

					<li>
						<a class="btn" id="panierHeader" data-bs-toggle="offcanvas" href="#panier_sideMenu" role="button" aria-controls="panier_sideMenu">
							<i style="font-size:1.5em;" class="bi bi-bag"></i>
							<span class="badge bg-danger" style="position: relative; top: -10px; left: -10px; font-size: 0.8em;">
								<?= $totalItems ?>
							</span>
						</a>
					</li>

					<!-- <li><a href="<?= site_url('panier'); ?>"><i style="font-size:1.5em;" class="bi bi-bag"></i></a></li> -->
				</div>
			</ul>
		</nav>
	</header>

<?php include('panier_sideMenu.php');