<?php

namespace App\Controllers\Product;

use App\Controllers\BaseController;
use App\Controllers\Media\MediaController;
use App\Models\MediaModel;
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
        $data = [
            'pageTitle' => 'Produits',
            'products' => $this->productModel->findAll(),
            'content'   => view('Admin/products') // Contenu principal
        ];

        return View('layout/main', $data);


    }
    public function ajouterProduitPost()
    {
        $productModel = new ProductModel();
        $mediaController = new MediaController();

        // Récupérer les données du formulaire
        $data = $this->request->getPost();
        $file = $this->request->getFile('new_img');
        $imageId = null;

        // Priorité : Image existante
        if (!empty($data['existing_img'])) {
            $imageId = $data['existing_img'];
        }
        // Sinon, uploader une nouvelle image via MediaController
        elseif ($file) {
            $imageId = $mediaController->uploadImage($file);
        }

        if ($imageId) {
            // Ajouter les informations du produit
            $data['id_img'] = $imageId;

            if ($productModel->save($data)) {
                return redirect()->to('/admin/products')->with('success', 'Produit ajouté avec succès.');
            }
        }

        return redirect()->back()->with('error', 'Erreur lors de l\'ajout du produit.');
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
        $data = $this->request->getPost();

        if (!$this->productModel->update($data['id_prod'], $data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->productModel->errors()
            ]);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Produit modifié.']);
    }

    public function modifierProduitGet($id_prod)
    {
        $data = [
            'pageTitle' => 'Modifier Produit',
            'product' => $this->productModel->find($id_prod)
        ];

        echo view('commun/header', $data);

        echo view('commun/footer');

        return view('Admin/edit_product', $data);
    }

    // Supprimer un produit
    public function supprimerProduit()
    {
        $id_prod = $this->request->getPost('id_prod');

        if (!$this->productModel->delete($id_prod)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Erreur lors de la suppression.']);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Produit supprimé.']);
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