<?php

	namespace App\Controllers\Boutique;

	use App\Controllers\BaseController;
	use App\Models\CategoryModel;
	use App\Models\ProductModel;

	class BoutiqueController extends BaseController
	{
		public function index()
		{
			$data = [
				'pageTitle' => 'Boutique'
			];

			echo view('commun/header', $data);
			echo view('Boutique/boutique');
			echo view('commun/footer');


		}

		public function categorie($categoryName)
		{
			$categoryModel = new CategoryModel();
			$productModel = new ProductModel();

			$category = $categoryModel->where('cat_name', $categoryName)->first();
			$categories = $categoryModel->findAll();
			if (!$categories) {
				return redirect()->to('/boutique')->with('error', 'Catégorie non trouvée');
			}

			$products = $productModel
				->select('product.*, image.img_path')
				->join('product_category', 'product.id_prod = product_category.id_prod')
				->join('image', 'image.id_img = product.id_img')
				->where('product_category.id_cat', $category['id_cat'])
				->findAll();

			// $products = $productModel
			// ->select('product.*, image.img_path')
			// ->join('product_category', 'product.id_prod = product_category.id_prod')
			// ->join('product_image', 'product.id_prod = product_image.id_prod')
			// ->join('image', 'image.id_img = product_image.id_img') 
			// ->where('product_category.id_cat', $category['id_cat'])
			// ->findAll();


			$data = [
				'categories' => $categories,
				'category' => $category,
				'products' => $products
			];

			echo view('commun/header', $data);
			echo view('Boutique/categorie', $data);
			echo view('commun/footer');
		}
	}
?>