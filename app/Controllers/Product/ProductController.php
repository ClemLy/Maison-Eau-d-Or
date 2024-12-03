<?php

namespace App\Controllers\Product;

use App\Controllers\BaseController;
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
    // Ajouter un produit
    public function ajouterProduitPost()
    {
        $data = $this->request->getPost();

        if (!$this->productModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->productModel->errors()
            ]);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Produit ajouté.']);
    }

    public function ajouterProduitGet()
    {
        $data = [
            'pageTitle' => 'Ajouter Produit'
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