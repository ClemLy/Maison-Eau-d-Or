<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;

class Home extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();

        $starProduct = $productModel->getStarProduct();
        $categories = $categoryModel->findAll();
        $defaultCategoryId = isset($categories[0]['id_cat']) ? $categories[0]['id_cat'] : null;  // Vérification si les catégories existent

        $productsByCategory = [];
        foreach ($categories as $category) {
            // Récupérer les produits pour chaque catégorie
            $products = $productModel
                ->select('product.*, image.img_path')
                ->join('product_category', 'product.id_prod = product_category.id_prod')
                ->join('product_image', 'product.id_prod = product_image.id_prod')
                ->join('image', 'image.id_img = product_image.id_img')
                ->where('product_category.id_cat', $category['id_cat'])
                ->findAll();

            // Si des produits sont trouvés, les ajouter à la liste, sinon, mettre un tableau vide
            $productsByCategory[$category['id_cat']] = !empty($products) ? $products : [];
        }

        // Récupérer l'ID de la catégorie sélectionnée, ou la première catégorie par défaut
        $selectedCategoryId = isset($_GET['category_id']) ? (int) $_GET['category_id'] : $defaultCategoryId;

        // Si la catégorie sélectionnée a des produits, les afficher, sinon afficher un tableau vide
        $selectedProducts = isset($productsByCategory[$selectedCategoryId]) ? $productsByCategory[$selectedCategoryId] : [];

        $data = [
            'pageTitle' => 'Accueil',
            'categories' => $categories,
            'selectedProducts' => $selectedProducts,
            'starProduct' => $starProduct,
            'defaultCategoryId' => $defaultCategoryId,
            'selectedCategoryId' => $selectedCategoryId  // Passer l'ID de la catégorie sélectionnée
        ];

        echo view('commun/header', $data);
        echo view('Home/home', $data);
        echo view('commun/footer');
    }
}
