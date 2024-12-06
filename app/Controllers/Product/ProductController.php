<?php

namespace App\Controllers\Product;

use App\Controllers\BaseController;
use App\Controllers\Media\MediaController;
use App\Models\CategoryModel;
use App\Models\MediaModel;
use App\Models\ProductCategoryModel;
use App\Models\ProductImageModel;
use App\Models\ProductModel;

class ProductController extends BaseController
{
    private ProductModel $productModel;
    private MediaModel $mediaModel;


    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->mediaModel = new MediaModel();
    }
    // Liste des produits
    public function index()
    {
        $products = $this->productModel->getProducts();
        $data = [
            'pageTitle' => 'Produits',
            'content'   => view('Admin/products',
                [
                    'products' => $products
                ]
            ) // Contenu principal
        ];

        return View('Layout/main', $data);

    }
    public function ajouterProduitPost()
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();
        $productCategoryModel = new ProductCategoryModel();
        $productImageModel = new ProductImageModel();

        // Ajouter un log pour démarrer la méthode
        log_message('info', 'Début de la méthode ajouterProduitPost.');

        // Récupérer les données du formulaire
        $data = $this->request->getPost();
        $imageIds = []; // Tableau pour stocker les IDs des images

        try {
            // Log des données reçues
            log_message('info', 'Données du formulaire : ' . print_r($data, true));

            // Récupérer les images existantes sélectionnées
            if (!empty($data['existing_imgs'])) {
                // Si c'est une chaîne, la convertir en tableau
                $imageIds = is_array($data['existing_imgs'])
                    ? $data['existing_imgs']
                    : explode(',', $data['existing_imgs']);
            }

            // Vérification après conversion
            log_message('info', 'Images sélectionnées après traitement : ' . implode(', ', $imageIds));

            // Vérifier si des images ont été sélectionnées
            if (empty($imageIds)) {
                log_message('error', 'Aucune image sélectionnée.');
                throw new \RuntimeException("Aucune image sélectionnée.");
            }

            // Ajouter les informations du produit
            log_message('info', 'Tentative d\'enregistrement du produit.');
            if (!$productModel->save($data)) {
                $errors = $productModel->errors();
                log_message('error', 'Erreur lors de l\'enregistrement du produit : ' . implode(', ', $errors));
                throw new \RuntimeException(implode(', ', $errors));
            }
            log_message('info', 'Produit enregistré avec succès.');

            // Récupérer l'ID du produit ajouté
            $productId = $productModel->getInsertID();
            log_message('info', 'Produit ajouté avec l\'ID : ' . $productId);

            // Associer les images sélectionnées au produit
            foreach ($imageIds as $imageId) {
                $s= ['id_prod' => $productId, 'id_img' => (int) trim($imageId)];

                if (!$productImageModel->saveComposite($s)) {
                    log_message('error', 'Erreur lors de l\'association de l\'image ID ' . $imageId . ' au produit ID ' . $productId);
                    throw new \RuntimeException("Erreur lors de l'association de l'image ID $imageId au produit.");
                }

                log_message('info', 'Image ID ' . $imageId . ' associée au produit ID ' . $productId);
            }
            log_message('info', 'Images associées au produit.');


            $categories = !empty($data['categories']) ? json_decode($data['categories'], true) : [];
            log_message('info', 'Catégories reçues : ' . print_r($categories, true));

            if (!empty($categories) && is_array($categories)) {
                foreach ($categories as $categoryName) {
                    $categoryName = trim($categoryName);

                    // Vérifier si la catégorie existe déjà
                    $category = $categoryModel->where('cat_name', $categoryName)->first();
                    $categoryId = $category ? $category['id_cat'] : null;

                    // Si la catégorie n'existe pas, la créer
                    if (!$categoryId) {
                        if (!$categoryModel->save(['cat_name' => $categoryName])) {
                            log_message('error', 'Erreur lors de la création de la catégorie : ' . $categoryName);
                            throw new \RuntimeException("Erreur lors de la création de la catégorie '{$categoryName}'.");
                        }
                        $categoryId = $categoryModel->getInsertID();
                        log_message('info', 'Nouvelle catégorie créée avec ID : ' . $categoryId);
                    }

                    // Associer la catégorie au produit
                    if (!$productCategoryModel->saveComposite(['id_prod' => $productId, 'id_cat' => $categoryId])) {
                        log_message('error', 'Erreur lors de l\'association de la catégorie ID ' . $categoryId . ' au produit.');
                        throw new \RuntimeException("Erreur lors de l'association des catégories au produit.");
                    }
                }
            }

            // Log de fin de méthode
            log_message('info', 'Produit ajouté avec succès.');

            return redirect()->to('/admin/produits')->with('success', 'Produit ajouté avec succès.');

        } catch (\RuntimeException $e) {
            log_message('error', 'Exception capturée : ' . $e->getMessage());
            return redirect()->to('/admin/produit/ajouter')
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
    public function ajouterProduitGet()
    {

        $images = $this->mediaModel->findAll(); // Récupérer toutes les images

        $data = [
            'pageTitle' => 'Modifier Produit',
            'content'   => view('Admin/add_product',
                [
                    'images'=>$images
                ]
            ) // Contenu principal
        ];
        return View('Layout/main', $data);


    }

    public function modifierProduitPost()
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();
        $productCategoryModel = new ProductCategoryModel();
        $mediaController = new MediaController();

        $data = $this->request->getPost();
        $file = $this->request->getFile('new_img');
        $productId = $data['id_prod'] ?? null;

        $db = \Config\Database::connect();
        $db->transStart(); // Démarre une transaction

        try {
            // Vérifier si le produit existe
            $product = $productModel->find($productId);
            if (!$product) {
                throw new \RuntimeException('Produit introuvable.');
            }

            // Gestion de l'image
            $imageId = $data['existing_img'] ?? $product['id_img'];
            if ($file && $file->isValid()) {
                $imageId = $mediaController->uploadImage($file);
                if (!$imageId) {
                    throw new \RuntimeException("L'upload de l'image a échoué.");
                }
            }
            $data['id_img'] = $imageId;

            // Mise à jour du produit
            $data['on_sale'] = isset($data['on_sale']) ? 't' : 'f';
            $data['is_star'] = isset($data['is_star']) ? 't' : 'f';

            if (!$productModel->update($productId, $data)) {
                throw new \RuntimeException(implode(', ', $productModel->errors()));
            }

            // Récupérer les relations existantes
            $existingCategories = $productCategoryModel->where('id_prod', $productId)->findAll();
            $existingCategoryIds = array_column($existingCategories, 'id_cat');

            $newCategoryIds = [];
            $categoryInput = $data['categories'] ?? '[]';
            $categoryNames = json_decode($categoryInput, true);

            if (!empty($categoryNames) && is_array($categoryNames)) {
                foreach ($categoryNames as $categoryName) {
                    $categoryName = trim($categoryName);

                    // Vérifier si la catégorie existe
                    $category = $categoryModel->where('cat_name', $categoryName)->first();
                    $categoryId = $category['id_cat'] ?? null;

                    // Créer la catégorie si elle n'existe pas
                    if (!$categoryId) {
                        if (!$categoryModel->insert(['cat_name' => $categoryName])) {
                            throw new \RuntimeException("Erreur lors de la création de la catégorie : {$categoryName}");
                        }

                        // Récupérer l'ID après insertion réussie
                        $categoryId = $categoryModel->getInsertID();

                        // Double vérification si `insertID` échoue
                        if (!$categoryId) {
                            $category = $categoryModel->where('cat_name', $categoryName)->first();
                            $categoryId = $category['id_cat'] ?? null;

                            if (!$categoryId) {
                                throw new \RuntimeException("Impossible de récupérer l'ID pour la catégorie : {$categoryName}");
                            }
                        }
                    }

                    log_message('debug', "Catégorie traitée : ID = {$categoryId}, Nom = {$categoryName}");

                    $newCategoryIds[] = $categoryId;

                    // Vérifier si la relation existe déjà
                    $relationExists = $productCategoryModel
                        ->where(['id_prod' => $productId, 'id_cat' => $categoryId])
                        ->countAllResults();

                    if ($relationExists == 0) {
                        // Ajouter la relation uniquement si elle n'existe pas
                        if (!$productCategoryModel->insert(['id_prod' => $productId, 'id_cat' => $categoryId])) {
                            throw new \RuntimeException("Erreur lors de l'ajout de la relation produit-catégorie : produit {$productId}, catégorie {$categoryId}");
                        }
                        log_message('debug', "Relation ajoutée : produit {$productId}, catégorie {$categoryId}");
                    } else {
                        log_message('debug', "Relation déjà existante : produit {$productId}, catégorie {$categoryId}");
                    }
                }
            }

            // Supprimer les relations obsolètes
            $categoriesToRemove = array_diff($existingCategoryIds, $newCategoryIds);
            if (!empty($categoriesToRemove)) {
                $productCategoryModel->where('id_prod', $productId)
                    ->whereIn('id_cat', $categoriesToRemove)
                    ->delete();
                log_message('debug', 'Relations supprimées pour les catégories : ' . implode(', ', $categoriesToRemove));
            }

            $db->transComplete();
            if ($db->transStatus() === false) {
                throw new \RuntimeException("Erreur lors de la transaction.");
            }

            return redirect()->to('/admin/produits')->with('success', 'Produit modifié avec succès.');
        } catch (\RuntimeException $e) {
            $db->transRollback(); // Annuler les modifications
            log_message('error', $e->getMessage());
            return redirect()->to('/admin/produit/modifier/' . $productId)
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
    public function modifierProduitGet($id_prod)
    {
        $productModel = new ProductModel();
        $imageModel = new \App\Models\MediaModel(); // Charger le modèle Image

        // Récupérer le produit par son ID
        $product = $productModel->getProductById($id_prod);
        if (!$product) {
            return redirect()->to('/admin/produits')->with('error', 'Produit introuvable.');
        }

        $images = $imageModel->findAll();

        $data = [
            'pageTitle' => 'Modifier Produit',
            'content'   => view('Admin/edit_product',
                [   'product' => $product,
                    'images'=>$images
                ]
            ) // Contenu principal
        ];

        return View('Layout/main', $data);
    }

    // Supprimer un produit
    public function supprimerProduitGet($id_prod)
    {
        try {
            // Vérifier si l'ID du produit est valide
            if ($id_prod === null || !$this->productModel->find($id_prod)) {
                return redirect()->to('/admin/produits')->with('error', 'Produit introuvable.');
            }

            // Supprimer le produit
            if (!$this->productModel->delete($id_prod)) {
                return redirect()->to('/admin/produits')->with('error', 'Erreur lors de la suppression du produit.');
            }

            return redirect()->to('/admin/produits')->with('success', 'Produit supprimé avec succès.');
        } catch (\RuntimeException $e) {
            return redirect()->to('/admin/produits')->with('error', $e->getMessage());
        }
    }

    // Activer un produit (on_sale = true)
    public function activerProduit()
    {
        $id_prod = $this->request->getPost('id_prod');

        if (!$this->productModel->update($id_prod, ['on_sale' => true])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Erreur lors de l\'activation.']);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Produit activé.']);
    }

    // Désactiver un produit (on_sale = false)
    public function desactiverProduit()
    {
        $id_prod = $this->request->getPost('id_prod');

        if (!$this->productModel->update($id_prod, ['on_sale' => false])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Erreur lors de la désactivation.']);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Produit désactivé.']);
    }
}