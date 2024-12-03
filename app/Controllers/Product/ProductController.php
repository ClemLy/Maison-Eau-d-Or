<?php

namespace App\Controllers\Product;

use App\Controllers\BaseController;
use App\Controllers\Media\MediaController;
use App\Models\CategoryModel;
use App\Models\MediaModel;
use App\Models\ProductCategoryModel;
use App\Models\ProductModel;

class ProductController extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }
    // Liste des produits
    public function index()
    {
        $products = $this->productModel->getProducts();
        $data = [
            'pageTitle' => 'Produits',
            'products' => $products,
            'content'   => view('Admin/products', ['products' => $products]) // Contenu principal
        ];

        return View('Layout/main', $data);

    }
    public function ajouterProduitPost()
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();
        $productCategoryModel = new ProductCategoryModel();
        $mediaController = new MediaController();

        // Récupérer les données du formulaire
        $data = $this->request->getPost();
        $file = $this->request->getFile('new_img');
        $imageId = null;

        try {
            // Gestion de l'image
            if (!empty($data['existing_img'])) {
                $imageId = $data['existing_img'];
            } elseif ($file && $file->isValid()) {
                $imageId = $mediaController->uploadImage($file);
                if (!$imageId) {
                    throw new \RuntimeException("L'upload de l'image a échoué.");
                }
            }

            if (!$imageId) {
                throw new \RuntimeException("Aucune image sélectionnée ou uploadée.");
            }

            // Ajouter les informations du produit
            $data['id_img'] = $imageId;

            if (!$productModel->save($data)) {
                $errors = $productModel->errors(); // Récupérer les erreurs de validation
                throw new \RuntimeException(implode(', ', $errors));
            }

            // Récupérer l'ID du produit ajouté
            $productId = $productModel->getInsertID();

            // Gestion des catégories
            $categoryInput = $data['categories'] ?? '[]'; // Catégories envoyées en JSON
            $categoryNames = json_decode($categoryInput, true); // Convertir le JSON en tableau PHP

            if (!empty($categoryNames) && is_array($categoryNames)) {
                foreach ($categoryNames as $categoryName) {
                    $categoryName = trim($categoryName);

                    // Vérifier si la catégorie existe déjà
                    $category = $categoryModel->where('cat_name', $categoryName)->first();
                    $categoryId = $category ? $category['id_cat'] : null;

                    // Si la catégorie n'existe pas, la créer
                    if (!$categoryId) {
                        $categoryModel->save(['cat_name' => $categoryName]);
                        $categoryId = $categoryModel->getInsertID();
                    }

                    // Ajouter la liaison dans la table product_category
                    $productCategoryModel->save(['id_prod' => $productId, 'id_cat' => $categoryId]);
                }
            }

            return redirect()->to('/admin/produits')->with('success', 'Produit ajouté avec succès.');
        } catch (\RuntimeException $e) {
            // Renvoyer l'erreur avec un message flash
            return redirect()->to('/admin/produit/ajouter')->withInput()->with('error', $e->getMessage());
        }
    }
    public function ajouterProduitGet()
    {
        $imageModel = new \App\Models\MediaModel(); // Charger le modèle Image

        $data = [
            'pageTitle' => 'Ajouter Produit',
            'images' => $imageModel->findAll(), // Récupérer toutes les images
        ];

        echo view('commun/header', $data);

        echo view('commun/footer');

        return view('Admin/add_product');
    }



    // Modifier un produit
    public function modifierProduitPost()
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();
        $productCategoryModel = new ProductCategoryModel();
        $mediaController = new MediaController();

        // Récupérer les données du formulaire
        $data = $this->request->getPost();
        $file = $this->request->getFile('new_img');
        $productId = $data['id_prod'] ?? null;

        try {
            // Vérifier si le produit existe
            $product = $productModel->find($productId);
            if (!$product) {
                throw new \RuntimeException('Produit introuvable.');
            }

            // Gestion de l'image
            if (!empty($data['existing_img'])) {
                $imageId = $data['existing_img'];
            } elseif ($file && $file->isValid()) {
                $imageId = $mediaController->uploadImage($file);
                if (!$imageId) {
                    throw new \RuntimeException("L'upload de l'image a échoué.");
                }
                $data['id_img'] = $imageId;
            }

            // Mise à jour des champs booléens
            $data['on_sale'] = isset($data['on_sale']) && $data['on_sale'] === 't' ? 't' : 'f';
            $data['is_star'] = isset($data['is_star']) && $data['is_star'] === 't' ? 't' : 'f';

            // Mise à jour du produit
            $data['id_img'] = $imageId ?? $product['id_img'];
            if (!$productModel->update($productId, $data)) {
                throw new \RuntimeException(implode(', ', $productModel->errors()));
            }

            // Gestion des catégories
            $categoryInput = $data['categories'] ?? '[]';
            $categoryNames = json_decode($categoryInput, true);

            // Supprimer les anciennes relations
            $productCategoryModel->where('id_prod', $productId)->delete();

            if (!empty($categoryNames) && is_array($categoryNames)) {
                foreach ($categoryNames as $categoryName) {
                    $categoryName = trim($categoryName);

                    // Vérifier si la catégorie existe
                    $category = $categoryModel->where('cat_name', $categoryName)->first();
                    $categoryId = $category['id_cat'] ?? null;

                    // Si la catégorie n'existe pas, la créer
                    if (!$categoryId) {
                        if (!$categoryModel->save(['cat_name' => $categoryName])) {
                            throw new \RuntimeException("Erreur lors de l'ajout de la catégorie.");
                        }
                        $categoryId = $categoryModel->getInsertID();
                    }

                    // Ajouter la relation dans product_category
                    if (!$productCategoryModel->save(['id_prod' => $productId, 'id_cat' => $categoryId])) {
                        throw new \RuntimeException("Erreur lors de la liaison produit-catégorie.");
                    }
                }
            }

            return redirect()->to('/admin/produits')->with('success', 'Produit modifié avec succès.');
        } catch (\RuntimeException $e) {
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