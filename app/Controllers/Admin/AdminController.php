<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AProposModel;
use App\Models\FaqModel;
use App\Models\BlogModel;
use App\Models\MediaModel;
use App\Models\OrderModel;
use App\Models\OrderProductModel;

class AdminController extends BaseController
{

    public function index()
    {
        $adminRoutes = [
            'Produits' => [
                [
                    'logo'  => 'bi bi-pencil-square',
                    'url'   => '/admin/produits',
                    'label' => 'Gestions des produits',
                ],
                [
                    'logo'  => 'bi bi-plus-square',
                    'url'   => '/admin/produit/ajouter',
                    'label' => 'Ajouter un produit',
                ],
            ],
            'Communication' => [
                [
                    'logo'  => 'bi bi-chat-left-text',
                    'url'   => '/admin/blog',
                    'label' => 'Gestion du blog',
                ],
                [
                    'logo'  => 'bi bi-newspaper',
                    'url'   => '/admin/blog',
                    'label' => 'Ajouter un article sur le blog',
                ],
                [
                    'logo'  => 'bi bi-question-square',
                    'url'   => '/admin/faq/modifier',
                    'label' => 'Modifier la FAQ',
                ],
                [
                    'logo'  => 'bi bi-info-circle',
                    'url'   => '/admin/a-propos/modifier',
                    'label' => 'Modifier À propos',
                ],
            ],
            'Autres' => [
                [
                    'logo'  => 'bi bi-house-gear',
                    'url'   => '/admin/carrousel',
                    'label' => 'Modifier les carrousels',
                ],
                [
                    'logo'  => 'bi bi-list-ul',
                    'url'   => '/admin/commandes',
                    'label' => 'Liste des commandes',
                ],
                [
                    'logo'  => 'bi bi-images',
                    'url'   => '/admin/gestionImage',
                    'label' => 'Gestion des images',
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




    /* ------------------ */
    /* -----À PROPOS----- */
    /* ------------------ */

    public function modifierAProposGet()
    {
        // Charger le modèle
        $aProposModel = new \App\Models\AProposModel();
    
        // Récupérer le contenu avec ID = 1
        $contentData = $aProposModel->find(1);
    
        // Préparer les données pour la vue partielle
        $aproposViewData = [
            'currentContent' => $contentData['content'] ?? '', // Contenu existant ou valeur par défaut
        ];
    
        // Préparer les données pour le layout principal
        $data = [
            'pageTitle' => 'Modifier À Propos',
            'content'   => view('Admin/edit_apropos', $aproposViewData), // Charger la vue partielle avec ses données
        ];
    
        // Charger la vue principale avec le contenu
        return view('Layout/main', $data);
    }

    public function modifierAProposPost()
    {
        $aProposModel = new AProposModel(); // Instanciation du modèle

        // Vérifier si la requête est AJAX
        if ($this->request->isAJAX())
        {
            // Récupérer les données POST
            $content = $this->request->getPost('content');

            if (empty($content))
            {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Le contenu ne peut pas être vide.',
                ]);
            }

            // Vérifier si une ligne avec id_apropos = 1 existe
            $existingData = $aProposModel->find(1);

            if ($existingData)
            {
                // Mettre à jour le contenu si la ligne existe
                $updated = $aProposModel->update(1, ['content' => $content]);

                if ($updated)
                {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'message' => 'Le contenu a été mis à jour avec succès.',
                    ]);
                }
                else
                {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Une erreur est survenue lors de la mise à jour.',
                    ]);
                }
            } 
            else
            {
                // Insérer une nouvelle ligne si elle n'existe pas
                $inserted = $aProposModel->insert(['id' => 1, 'content' => $content]);

                if ($inserted)
                {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'message' => 'Le contenu a été créé avec succès.',
                    ]);
                }
                else
                {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Une erreur est survenue lors de la création du contenu.',
                    ]);
                }
            }
        }

        // Retourner une erreur si ce n'est pas une requête AJAX
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Requête invalide.',
        ]);
    }

    /* ------------------ */
    /* -------FAQ-------- */
    /* ------------------ */

    public function modifierFaqGet()
    {
        // Charger le modèle
        $faqModel = new \App\Models\FaqModel();
    
        // Récupérer le contenu avec ID = 1
        $contentData = $faqModel->find(1);
    
        // Préparer les données pour la vue partielle
        $faqViewData = [
            'currentContent' => $contentData['content'] ?? '', // Contenu existant ou valeur par défaut
        ];
    
        // Préparer les données pour le layout principal
        $data = [
            'pageTitle' => 'Modifier FAQ',
            'content'   => view('Admin/edit_faq', $faqViewData), // Charger la vue partielle avec ses données
        ];
    
        // Charger la vue principale avec le contenu
        return view('Layout/main', $data);
    }

    public function modifierFaqPost()
    {
        $faqModel = new FaqModel(); // Instanciation du modèle

        // Vérifier si la requête est AJAX
        if ($this->request->isAJAX())
        {
            // Récupérer les données POST
            $content = $this->request->getPost('content');

            if (empty($content))
            {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Le contenu ne peut pas être vide.',
                ]);
            }

            // Vérifier si une ligne avec id_apropos = 1 existe
            $existingData = $faqModel->find(1);

            if ($existingData)
            {
                // Mettre à jour le contenu si la ligne existe
                $updated = $faqModel->update(1, ['content' => $content]);

                if ($updated)
                {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'message' => 'Le contenu a été mis à jour avec succès.',
                    ]);
                }
                else
                {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Une erreur est survenue lors de la mise à jour.',
                    ]);
                }
            } 
            else
            {
                // Insérer une nouvelle ligne si elle n'existe pas
                $inserted = $faqModel->insert(['id' => 1, 'content' => $content]);

                if ($inserted)
                {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'message' => 'Le contenu a été créé avec succès.',
                    ]);
                }
                else
                {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Une erreur est survenue lors de la création du contenu.',
                    ]);
                }
            }
        }

        // Retourner une erreur si ce n'est pas une requête AJAX
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Requête invalide.',
        ]);
    }



    /* ------------------ */
    /* -------Blog------- */
    /* ------------------ */

    // Liste des produits
    public function blog()
    {
        $blogModel = new BlogModel();
        $mediaModel = new MediaModel();

        $blog = $blogModel->getArticle();
        $images = $mediaModel->findAll();
        $data = [
            'pageTitle' => 'Gestion Blog',
            'content'   => view('Admin/articles',
                [
                    'articles' => $blog,
                    'images' => $images
                ]
            ) // Contenu principal
        ];

        return View('Layout/main', $data);

    }

    /* ------------------ */
    /* ----Historique---- */
    /* ------------------ */


	public function historique_admin()
    {
        $orderModel = new OrderModel();
        $orderProductModel = new OrderProductModel();


        $orders = $orderModel->findAll();

        foreach ($orders as &$order) {
            $order['products'] = $orderProductModel->getProductsByOrder($order['id_order']);
        }

        $data = [
            'pageTitle' => 'Historique',
            'orders' => $orders
        ];

        echo view('commun/header');
        echo view('Admin/read_historique', $data);
        echo view('commun/footer');
    }


}