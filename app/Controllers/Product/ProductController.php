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
        $productImageModel = new ProductImageModel();

        log_message('info', 'Début de la méthode modifierProduitPost.');

        $data = $this->request->getPost();
        $productId = $data['id_prod'] ?? null;
        $imageIds = []; // Tableau pour stocker les IDs des images

        try {
            // Vérification de l'existence du produit
            log_message('info', 'Vérification de l\'existence du produit ID : ' . $productId);
            $product = $productModel->find($productId);
            if (!$product) {
                log_message('error', 'Produit introuvable avec l\'ID : ' . $productId);
                throw new \RuntimeException("Produit introuvable.");
            }

            log_message('info', 'Produit trouvé : ' . print_r($product, true));

            // Gestion des images sélectionnées
            if (!empty($data['existing_imgs'])) {
                $imageIds = is_array($data['existing_imgs'])
                    ? $data['existing_imgs']
                    : explode(',', $data['existing_imgs']);
            }
            log_message('info', 'Images sélectionnées après traitement : ' . implode(', ', $imageIds));

            // Vérifier si des images ont été sélectionnées
            if (empty($imageIds)) {
                log_message('error', 'Aucune image sélectionnée.');
                throw new \RuntimeException("Aucune image sélectionnée.");
            }

            // Gestion des relations produit-image
            $currentImageIds = $productImageModel->where('id_prod', $productId)->findAll();
            $currentImageIds = array_column($currentImageIds, 'id_img');
            log_message('info', 'Images actuellement associées au produit : ' . implode(', ', $currentImageIds));

            // Ajouter les nouvelles relations produit-image
            $newImageIds = array_diff($imageIds, $currentImageIds);
            foreach ($newImageIds as $imageId) {
                if (!$productImageModel->saveComposite(['id_prod' => $productId, 'id_img' => (int)$imageId])) {
                    log_message('error', 'Erreur lors de l\'ajout de la relation produit-image : Produit ' . $productId . ', Image ' . $imageId);
                    throw new \RuntimeException("Erreur lors de l'association de l'image ID {$imageId} au produit.");
                }
                log_message('info', 'Nouvelle relation ajoutée : Produit ' . $productId . ', Image ' . $imageId);
            }

            // Supprimer les relations obsolètes
            $obsoleteImageIds = array_diff($currentImageIds, $imageIds);
            foreach ($obsoleteImageIds as $imageId) {
                if (!$productImageModel->deleteComposite($productId, $imageId)) {
                    log_message('error', 'Erreur lors de la suppression de la relation produit-image : Produit ' . $productId . ', Image ' . $imageId);
                    throw new \RuntimeException("Erreur lors de la suppression de l'image ID {$imageId} pour le produit.");
                }
                log_message('info', 'Relation supprimée : Produit ' . $productId . ', Image ' . $imageId);
            }

            // Mise à jour des informations du produit
            $data['on_sale'] = isset($data['on_sale']) ? 't' : 'f';
            $data['is_star'] = isset($data['is_star']) ? 't' : 'f';

            log_message('info', 'Mise à jour des informations du produit.');
            if (!$productModel->update($productId, $data)) {
                $errors = $productModel->errors();
                log_message('error', 'Erreur lors de la mise à jour du produit : ' . implode(', ', $errors));
                throw new \RuntimeException(implode(', ', $errors));
            }

            // Gestion des catégories
            $categories = !empty($data['categories']) ? json_decode($data['categories'], true) : [];
            log_message('info', 'Catégories reçues : ' . print_r($categories, true));

            $existingCategories = $productCategoryModel->where('id_prod', $productId)->findAll();
            $existingCategoryIds = array_column($existingCategories, 'id_cat');
            log_message('info', 'Catégories existantes pour le produit : ' . implode(', ', $existingCategoryIds));

            $newCategoryIds = [];
            if (!empty($categories) && is_array($categories)) {
                foreach ($categories as $categoryName) {
                    $categoryName = trim($categoryName);

                    $category = $categoryModel->where('cat_name', $categoryName)->first();
                    $categoryId = $category['id_cat'] ?? null;

                    if (!$categoryId) {
                        if (!$categoryModel->insert(['cat_name' => $categoryName])) {
                            log_message('error', 'Erreur lors de la création de la catégorie : ' . $categoryName);
                            throw new \RuntimeException("Erreur lors de la création de la catégorie '{$categoryName}'.");
                        }
                        $categoryId = $categoryModel->getInsertID();
                        log_message('info', 'Nouvelle catégorie créée : ID ' . $categoryId . ', Nom ' . $categoryName);
                    }

                    $newCategoryIds[] = $categoryId;

                    if (!$productCategoryModel->where(['id_prod' => $productId, 'id_cat' => $categoryId])->countAllResults()) {
                        if (!$productCategoryModel->saveComposite(['id_prod' => $productId, 'id_cat' => $categoryId])) {
                            log_message('error', 'Erreur lors de l\'association produit-catégorie : Produit ' . $productId . ', Catégorie ' . $categoryId);
                            throw new \RuntimeException("Erreur lors de l'association de la catégorie {$categoryId} au produit.");
                        }
                        log_message('info', 'Nouvelle relation produit-catégorie ajoutée : Produit ' . $productId . ', Catégorie ' . $categoryId);
                    }
                }
            }

            $categoriesToRemove = array_diff($existingCategoryIds, $newCategoryIds);
            if (!empty($categoriesToRemove)) {
                $productCategoryModel->where('id_prod', $productId)
                    ->whereIn('id_cat', $categoriesToRemove)
                    ->delete();
                log_message('info', 'Relations supprimées pour les catégories : ' . implode(', ', $categoriesToRemove));
            }

            log_message('info', 'Produit modifié avec succès.');
            return redirect()->to('/admin/produits')->with('success', 'Produit modifié avec succès.');
        } catch (\RuntimeException $e) {
            log_message('error', 'Exception capturée : ' . $e->getMessage());
            return redirect()->to('/admin/produit/modifier/' . $productId)
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
    public function modifierProduitGet($id_prod)
    {

        // Récupérer le produit par son ID
        $product = $this->productModel->getProductById($id_prod);
        if (!$product) {
            return redirect()->to('/admin/produits')->with('error', 'Produit introuvable.');
        }

        $images = $this->mediaModel->findAll();

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