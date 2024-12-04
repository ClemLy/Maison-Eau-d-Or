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

        return view('Layout/main', $data);
    }

    public function modifierAProposGet()
    {
        // Lecture du fichier où le contenu est sauvegardé (ajustez selon votre logique)
        $filePath = WRITEPATH . 'apropos_content.html';
        $currentContent = file_exists($filePath) ? file_get_contents($filePath) : '';

        $data = [
            'pageTitle'       => 'Modifier À Propos',
            'currentContent'  => $currentContent,
            'content'         => view('Admin/edit_apropos')
        ];

        return view('Layout/main', $data);
    }

    public function modifierAProposPost()
    {
        $request = $this->request;
        if ($request->isAJAX())
        {
            $content = $request->getJSON()->content;

            // Simulation de sauvegarde (à adapter selon votre base de données)
            if ($content)
            {
                // Exemple : Sauvegarde dans un fichier ou une base de données
                file_put_contents(WRITEPATH . 'apropos_content.html', $content);
                return $this->response->setJSON(['status' => 'success', 'message' => 'Contenu sauvegardé avec succès !']);
            }
            return $this->response->setJSON(['status' => 'error', 'message' => 'Erreur lors de la sauvegarde !']);
        }
    }
}