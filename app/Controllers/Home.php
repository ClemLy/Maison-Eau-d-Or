<?php

	namespace App\Controllers;

	use App\Controllers\BaseController;
	use App\Models\ProductModel;
	use App\Models\CategoryModel;

	class Home extends BaseController
	{
		public function index()
		{
		
			$productModel  = new ProductModel();
			$categoryModel = new CategoryModel();

			$starPorduct = $productModel->getStarProduct();

			$categories = $categoryModel->findAll();

			$data = [
				'pageTitle' => 'Accueil',
				'starProduct' => $starPorduct,
				'categories' => $categories
			];

			echo view('commun/header', $data);
			echo view('Home/home', $data);
			echo view('commun/footer');
		}
	}
?>