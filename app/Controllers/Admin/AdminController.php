<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AProposModel;
use App\Models\FaqModel;
use App\Models\BlogModel;

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

        $blog = $blogModel->getArticle();
        $data = [
            'pageTitle' => 'Gestion Blog',
            'content'   => view('Admin/articles',
                [
                    'articles' => $blog
                ]
            ) // Contenu principal
        ];

        return View('Layout/main', $data);

    }

    public function modifierArticle($id_art)
    {
        helper(['form']);
        $blogModel = new BlogModel();

        // Vérifier si l'article existe
        $article = $blogModel->find($id_art);
        if (!$article)
        {
            return redirect()->to('/admin/blog')->with('error', 'Article introuvable.');
        }

        if ($this->request->getMethod() === 'POST')
        {
            $rules = [
                'title'   => 'required|max_length[255]',
                'content' => 'required',
            ];

            if (!$this->validate($rules))
            {
                return redirect()->back()->withInput()->with('validation', $this->validator);
            }

            // Mise à jour de l'article
            $updatedData = [
                'art_title' => $this->request->getPost('art_title'),
                'art_text'  => $this->request->getPost('content'),
            ];

            $blogModel->update($id_art, $updatedData);

            return redirect()->to('/admin/blog')->with('success', 'Article modifié avec succès.');
        }

        // Préparer les données pour la vue
        $data = [
            'pageTitle'      => 'Modifier Article',
            'currentContent' => $article['art_text'], // Le contenu existant de l'article
            'article'        => $article,
            'content'        => view('Admin/edit_article', [
                'currentContent' => $article['art_text'], // Injecter dans Quill
                'article'        => $article,
            ]),
        ];

        return view('Layout/main', $data);
    }


    public function supprimerArticle($id_art)
    {
        $blogModel = new \App\Models\BlogModel();

        // Vérifie si l'article existe
        $article = $blogModel->find($id_art);
        if (!$article)
        {
            return redirect()->to('/admin/blog')->with('error', 'L\'article n\'existe pas.');
        }

        // Supprime l'article
        if ($blogModel->delete($id_art))
        {
            return redirect()->to('/admin/blog')->with('success', 'L\'article a été supprimé avec succès.');
        }
        else
        {
            return redirect()->to('/admin/blog')->with('error', 'Une erreur est survenue lors de la suppression.');
        }
    }


}