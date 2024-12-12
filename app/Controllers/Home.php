<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\ShowcaseModel;
use App\Models\CarouselModel;

class Home extends BaseController
{
    public function index()
    {
        $productModel  = new ProductModel();
        $categoryModel = new CategoryModel();
        $showcaseModel = new ShowcaseModel();
        $carouselModel = new CarouselModel();

         // Récupérer les produits vedettes
        $starProduct = $productModel->getStarProduct();

        // Récupérer les catégories du showcase
        $categories = $showcaseModel
            ->select('category.id_cat, category.cat_name')
            ->join('category', 'showcase.id_cat = category.id_cat')
            ->orderBy('showcase.id_show', 'ASC') // Tri par position
            ->findAll();

        // Vérification si les catégories existent
        $defaultCategoryId = isset($categories[0]['id_cat']) ? $categories[0]['id_cat'] : null;  // Vérification si les catégories existent

        $productsByCategory = [];
        foreach ($categories as $category)
        {
// Récupérer les produits pour chaque catégorie, y compris les images
        $products = $productModel
            ->select('product.*, array_agg(image.img_path) as img_paths') // Utiliser array_agg pour grouper les images
            ->join('product_category', 'product.id_prod = product_category.id_prod')
            ->join('product_image', 'product.id_prod = product_image.id_prod')
            ->join('image', 'product_image.id_img = image.id_img')
            ->where('product_category.id_cat', $category['id_cat'])
            ->where('product.on_sale', 't')
            ->groupBy('product.id_prod') // Regrouper par produit
            ->get()
            ->getResultArray();

                

            // Si des produits sont trouvés, les ajouter à la liste, sinon, mettre un tableau vide
            $productsByCategory[$category['id_cat']] = !empty($products) ? $products : [];
        }

        // Récupérer l'ID de la catégorie sélectionnée, ou la première catégorie par défaut
        $selectedCategoryId = isset($_GET['category_id']) ? (int) $_GET['category_id'] : $defaultCategoryId;

        $selectedProducts = [];


        // Si la catégorie sélectionnée a des produits, les afficher, sinon afficher un tableau vide
        $selectedProducts = isset($productsByCategory[$selectedCategoryId]) ? $productsByCategory[$selectedCategoryId] : [];


        

        // Récupérer les images du carrousel principal
        $carouselImages = $carouselModel
            ->select('image.img_path, carousel.link_car')
            ->join('image', 'carousel.id_img = image.id_img')
            ->orderBy('carousel.id_car', 'ASC')
            ->findAll();


        
        $data = [
            'pageTitle'          => 'Accueil',
            'categories'         => $categories,
            'selectedProducts'   => $selectedProducts,
            'starProduct'        => $starProduct,
            'defaultCategoryId'  => $defaultCategoryId,
            'selectedCategoryId' => $this->request->getGet('category_id'),
            'carouselImages'     => $carouselImages
        ];

        echo view('commun/header', $data);
        echo view('Home/home', $data);
        echo view('commun/footer');
    }
}
