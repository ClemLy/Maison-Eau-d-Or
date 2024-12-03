<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->get('/', 'Home::index');

// Boutique
$routes->get('boutique', 'Boutique\BoutiqueController::index');
$routes->get('boutique/produit/(:num)', 'Boutique\BoutiqueController::produit/$1');
$routes->get('boutique/categorie/(:num)', 'Boutique\BoutiqueController::categorie/$1');


// À Propos
$routes->get('a-propos', 'APropos\AProposController::index');

// Blog
$routes->get('blog', 'Blog\BlogController::index');
$routes->get('blog/article/(:num)', 'Blog\BlogController::article/$1');

// FAQ
$routes->get('faq', 'Faq\FaqController::index');

// Compte
$routes->get('account', 'Compte\CompteController::index'); // affiche le profil
$routes->get('account/update', 'Compte\CompteController::update');
$routes->post('account/update', 'Compte\CompteController::update');
$routes->get('account/delete', 'Compte\CompteController::delete');
$routes->get('logout', 'Compte\CompteController::logout');

$routes->get('user/mot-de-passe-oublie', 'Compte\CompteController::motDePasseOublie'); // affiche le formulaire de mot de passe oublié
$routes->get('user/mot-de-passe-oublie/(:alphanum)', 'Compte\CompteController::motDePasseOublie/$1'); 
$routes->get('user/valider/(:alphanum)', 'Compte\CompteController::valider/$1'); // valider l'inscription
$routes->get('user/commandes', 'Compte\CompteController::commandes', ['filter' => 'auth']); // affiche les commandes de l'utilisateur

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


// Panier
$routes->get('panier', 'Panier\PanierController::index', ['filter' => 'auth']); 
$routes->post('panier/ajouter/', 'Panier\PanierController::ajouter', ['filter' => 'auth']);  // id_produit/qte
$routes->post('panier/supprimer/', 'Panier\PanierController::supprimer', ['filter' => 'auth']); // id_produit
$routes->post('panier/vider', 'Panier\PanierController::vider', ['filter' => 'auth']); // id_produit
$routes->post('panier/modifier/', 'Panier\PanierController::modifier', ['filter' => 'auth']); // id_produit/qte


// Admin 
$routes->get('admin', 'Admin\AdminController::index', ['filter' => 'admin']);

$routes->get('admin/produits', 'Admin\AdminController::produits', ['filter' => 'admin']);
$routes->post('admin/produit/ajouter', 'Admin\AdminController::ajouterProduit', ['filter' => 'admin']); // un produit entier
$routes->post('admin/produit/modifier/', 'Admin\AdminController::modifierProduit', ['filter' => 'admin']); // produit entier
$routes->post('admin/produit/supprimer/', 'Admin\AdminController::supprimerProduit', ['filter' => 'admin']); // id_produit
$routes->post('admin/produit/activer/', 'Admin\AdminController::activerProduit', ['filter' => 'admin']); // id_produit
$routes->post('admin/produit/desactiver/', 'Admin\AdminController::desactiverProduit', ['filter' => '  admin']); // id_produit

$routes->get('admin/categories', 'Admin\AdminController::categories', ['filter' => 'admin']);
$routes->post('admin/categorie/ajouter', 'Admin\AdminController::ajouterCategorie', ['filter' => 'admin']);
$routes->post('admin/categorie/modifier/', 'Admin\AdminController::modifierCategorie/$1', ['filter' => 'admin']); // categorie
$routes->post('admin/categorie/supprimer/', 'Admin\AdminController::supprimerCategorie/$1', ['filter' => 'admin']); // id_categorie


$routes->get('admin/commande/(:num)', 'Admin\AdminController::commandes/$1', ['filter' => 'admin']);
$routes->get('admin/commandes/', 'Admin\AdminController::commande/',['filter' => 'admin']);


$routes->get('admin/blog/ajouter', 'Admin\AdminController::ajouterArticle', ['filter' => 'admin']); 
$routes->post('admin/blog/ajouter', 'Admin\AdminController::ajouterArticle', ['filter' => 'admin']);

$routes->get('admin/blog/modifier/(:num)', 'Admin\AdminController::modifierArticle/$1', ['filter' => 'admin']); 
$routes->post('admin/blog/modifier/', 'Admin\AdminController::modifierArticle', ['filter' => 'admin']);
$routes->get('admin/blog/supprimer/(:num)', 'Admin\AdminController::supprimerArticle/$1', ['filter' => 'admin']);


$routes->post('admin/faq/ajouter', 'Admin\AdminController::ajouterQuestion', ['filter' => 'admin']);
$routes->post('admin/faq/modifier/', 'Admin\AdminController::modifierQuestion/$1', ['filter' => 'admin']);
$routes->post('admin/faq/supprimer/', 'Admin\AdminController::supprimerQuestion/$1', ['filter' => 'admin']);

$routes->get('admin/a-propos/modifier', 'Admin\AdminController::modifierAPropos', ['filter' => 'admin']);
$routes->post('admin/a-propos/modifier', 'Admin\AdminController::modifierAPropos', ['filter' => 'admin']);


$routes->post('admin/produit-vedette/ajouter', 'Admin\AdminController::ajouterProduitVedette', ['filter' => 'admin']);
$routes->post('admin/produit-vedette/supprimer', 'Admin\AdminController::supprimerProduitVedette/$1', ['filter' => 'admin']);
