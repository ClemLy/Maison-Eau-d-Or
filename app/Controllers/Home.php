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

        $productsByCategory = [];
        foreach ($categories as $category) {
            $productsByCategory[$category['id_cat']] = $productModel
                ->select('product.*, image.img_path')
                ->join('product_category', 'product.id_prod = product_category.id_prod')
                ->join('image', 'image.id_img = product.id_img')
                ->where('product_category.id_cat', $category['id_cat'])
                ->findAll();
        }

        $selectedCategoryId = isset($_GET['category_id']) ? (int) $_GET['category_id'] : null;

        $selectedProducts = [];
        if ($selectedCategoryId && isset($productsByCategory[$selectedCategoryId])) {
            $selectedProducts = $productsByCategory[$selectedCategoryId];
        }

        $data = [
            'pageTitle' => 'Accueil',
            'categories' => $categories,
            'selectedProducts' => $selectedProducts,
			'starProduct' => $starProduct
        ];

        echo view('commun/header', $data);
        echo view('Home/home', $data);
        echo view('commun/footer');
    }
}
?>
