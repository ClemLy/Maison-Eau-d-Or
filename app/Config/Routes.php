<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->get('/', 'Home::index');

// Boutique
$routes->get('boutique', 'Boutique\BoutiqueController::index');

// À Propos
$routes->get('a-propos', 'APropos\AProposController::index');

// Blog
$routes->get('blog', 'Blog\BlogController::index');

// FAQ
$routes->get('faq', 'Faq\FaqController::index');

// Compte
$routes->get('compte', 'Compte\CompteController::index');

// Panier
$routes->get('panier', 'Panier\PanierController::index');