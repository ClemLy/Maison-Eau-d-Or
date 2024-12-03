<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminController extends BaseController
{
    public function index()
    {
        $adminRoutes = [
            'Produits' => [
                [
                    'url'   => '/admin/produits',
                    'label' => 'Liste des produits',
                ],
                [
                    'url'   => '/admin/produit/ajouter',
                    'label' => 'Ajouter un produit',
                ],
                [
                    'url'   => '/admin/produit/modifier/{id}',
                    'label' => 'Modifier un produit (exemple)',
                ],
            ],
            'Catégories' => [
                [
                    'url'   => '/admin/categories',
                    'label' => 'Liste des catégories',
                ],
                [
                    'url'   => '/admin/categorie/ajouter',
                    'label' => 'Ajouter une catégorie',
                ],
            ],
            'Commandes' => [
                [
                    'url'   => '/admin/commandes',
                    'label' => 'Liste des commandes',
                ],
            ],
            'Blog' => [
                [
                    'url'   => '/admin/blog/ajouter',
                    'label' => 'Ajouter un article',
                ],
                [
                    'url'   => '/admin/blog/{id}',
                    'label' => 'Voir les articles',
                ],
            ],
            'FAQ' => [
                [
                    'url'   => '/admin/faq/ajouter',
                    'label' => 'Ajouter une question',
                ],
            ],
            'Autres' => [
                [
                    'url'   => '/admin/a-propos/modifier',
                    'label' => 'Modifier la page "À propos"',
                ],
            ],
        ];

        $data = [
            'pageTitle'    => 'Fonctionnalités Administratives',
            'adminRoutes'  => $adminRoutes,
            'content'      => view('Admin/index', ['adminRoutes' => $adminRoutes]), // Vue contenant les liens
        ];

        return view('layout/main', $data);
    }
}