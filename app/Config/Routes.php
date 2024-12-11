<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->get('/', 'Home::index');

$routes->post('/image/upload', 'Media\MediaController::uploadImage');

// Boutique
$routes->get('boutique', 'Boutique\BoutiqueController::index');
$routes->get('boutique/produit/(:num)', 'Boutique\BoutiqueController::produit/$1');
$routes->get('boutique/categorie/(:any)', 'Boutique\BoutiqueController::categorie/$1');


// À Propos
$routes->get('a-propos', 'APropos\AProposController::index');

// Blog
$routes->get('blog', 'Blog\BlogController::index');
$routes->get('blog/(:num)', 'Blog\BlogController::lireArticle/$1');

// FAQ
$routes->get('faq', 'Faq\FaqController::index');

// Compte
$routes->get('account', 'Compte\CompteController::index'); // affiche le profil
$routes->get('account/historique', 'Compte\CompteController::historique');
$routes->get('account/update', 'Compte\CompteController::update');
$routes->post('account/update', 'Compte\CompteController::update');
$routes->get('account/delete', 'Compte\CompteController::delete');
$routes->get('logout', 'Compte\CompteController::logout');

$routes->get('user/mot-de-passe-oublie', 'Compte\CompteController::motDePasseOublie'); // affiche le formulaire de mot de passe oublié
$routes->get('user/mot-de-passe-oublie/(:alphanum)', 'Compte\CompteController::motDePasseOublie/$1'); 
$routes->get('user/valider/(:alphanum)', 'Compte\CompteController::valider/$1'); // valider l'inscription
$routes->get('user/commandes', 'Compte\CompteController::commandes', ['filter' => 'auth']); // affiche les commandes de l'utilisateur

$routes->post('newsletter/subscribe', 'Compte\CompteController::subscribe', ['filter' => 'auth']);

// Connexion
$routes->get('/signin', 'Compte\SigninController::index');
$routes->match(['get', 'post'], 'signin/auth', 'Compte\SigninController::loginAuth');

// Inscription
$routes->get('/signup', 'Compte\SignupController::index');
$routes->match(['get', 'post'], 'signup/store', 'Compte\SignupController::store');
$routes->get('/activate/(:any)', 'Compte\SignupController::activate/$1');

// Réinitialisation de mot de passe
$routes->get('/forgot-password', 'Compte\ForgotPasswordController::index');
$routes->post('/forgot-password/send-reset-link', 'Compte\ForgotPasswordController::sendResetLink');
$routes->get('/reset-password/(:any)', 'Compte\ResetPasswordController::index/$1');
$routes->post('/reset-password/update', 'Compte\ResetPasswordController::updatePassword');


// Commander
$routes->get('commander', 'Commander\CommanderController::index', ['filter' => 'auth']);
$routes->get('order/pdf/(:num)', 'Commander\CommanderController::generatePDF/$1');
$routes->post('commander/valider_commande', 'Commander\CommanderController::valider_commande', ['filter' => 'auth']);


// Panier
$routes->get('panier', 'Panier\PanierController::panierGet', ['filter' => 'auth']); 
$routes->post('panier', 'Panier\PanierController::panierPost', ['filter' => 'auth']); 

$routes->post('panier/ajouter/(:num)', 'Panier\PanierController::ajouter/$1', ['filter' => 'auth']);  // id_prod

$routes->get('panier/supprimer/(:num)', 'Panier\PanierController::supprimer/$1', ['filter' => 'auth']); // id_produit
$routes->get('panier/vider', 'Panier\PanierController::vider', ['filter' => 'auth']); // id_produit
$routes->post('panier/modifier/(:num)', 'Panier\PanierController::modifier/$1/$2', ['filter' => 'auth']); // id_produit/qte

$routes->get('panier/actualiser', 'Panier\PanierController::actualiser', ['filter' => 'auth']);  // Route pour actualiser le panier

// Admin 
$routes->get('admin', 'Admin\AdminController::index', ['filter' => 'admin']);

$routes->get('admin/produits', 'Product\ProductController::index', ['filter' => 'admin']);

$routes->get('admin/produit/ajouter', 'Product\ProductController::ajouterProduitGet', ['filter' => 'admin']); // un produit entier
$routes->post('admin/produit/ajouter', 'Product\ProductController::ajouterProduitPost', ['filter' => 'admin']); // un produit entier

$routes->get('admin/produit/modifier/(:num)', 'Product\ProductController::modifierProduitGet/$1', ['filter' => 'admin']); // produit entier
$routes->post('admin/produit/modifier/', 'Product\ProductController::modifierProduitPost', ['filter' => 'admin']); // produit entier

$routes->get('admin/produit/supprimer/(:num)', 'Product\ProductController::supprimerProduitGet/$1', ['filter' => 'admin']); // id_produit
$routes->post('admin/produit/activer/', 'Product\ProductController::activerProduit', ['filter' => 'admin']); // id_produit
$routes->post('admin/produit/desactiver/', 'Product\ProductController::desactiverProduit', ['filter' => '  admin']); // id_produit

$routes->get('admin/carrousel', 'Carrousel\CarrouselController::index', ['filter' => 'admin']);
$routes->post('admin/carrousel/modifierMain', 'Carrousel\CarrouselController::updateMainCarousel', ['filter' => 'admin']);
$routes->post('admin/carrousel/modifierCategorie', 'Carrousel\CarrouselController::updateCategoryCarousel', ['filter' => 'admin']);


$routes->get('admin/commande/(:num)', 'Admin\AdminController::commandes/$1', ['filter' => 'admin']);

// Gestionc ommande 
$routes->get('admin/commandes', 'Admin\AdminController::historique_admin', ['filter' => 'admin']);

$routes->get('admin/blog', 'Admin\AdminController::blog', ['filter' => 'admin']);

$routes->get('admin/blog/ajouter', 'Blog\BlogController::ajouterArticleGet', ['filter' => 'admin']);
$routes->post('admin/blog/ajouter', 'Blog\BlogController::ajouterArticlePost', ['filter' => 'admin']);

$routes->get('admin/blog/modifier/(:num)', 'Blog\BlogController::modifierArticleGet/$1', ['filter' => 'admin']); 
$routes->post('admin/blog/modifier/', 'Blog\BlogController::modifierArticlePost', ['filter' => 'admin']);

$routes->get('admin/blog/supprimer/(:num)', 'Blog\BlogController::supprimerArticle/$1', ['filter' => 'admin']);


$routes->get('admin/faq/modifier', 'Admin\AdminController::modifierFaqGet', ['filter' => 'admin']);
$routes->post('admin/faq/modifier', 'Admin\AdminController::modifierFaqPost', ['filter' => 'admin']);

$routes->get('admin/a-propos/modifier', 'Admin\AdminController::modifierAProposGet', ['filter' => 'admin']);
$routes->post('admin/a-propos/modifier', 'Admin\AdminController::modifierAProposPost', ['filter' => 'admin']);

$routes->get('admin/gestionImage', 'Media\MediaController::manageImage', ['filter' => 'admin']);
$routes->get('admin/gestionImage/supprimer/(:num)', 'Media\MediaController::deleteImage/$1', ['filter' => 'admin']);
// Légal
$routes->get('conditions-generales', 'Legal\LegalController::conditionsGenerales');
$routes->get('politique-confidentialite', 'Legal\LegalController::politiqueConfidentialite');
$routes->get('contact', 'Legal\LegalController::contact');
$routes->post('contact/send', 'Legal\LegalController::sendContact');