<?php

	namespace App\Controllers\Boutique;

	use App\Controllers\BaseController;
	use App\Models\CategoryModel;
	use App\Models\ProductModel;

	class BoutiqueController extends BaseController
	{
		public function index($cat_name = null)
		{
			$productModel = new ProductModel();
			$categoryModel = new CategoryModel();

			$categories = $categoryModel->findAll();

            $products = $productModel->getProducts();

			$data = [
				'pageTitle' => 'Boutique',
				'categories' => $categories,
				'products' => $products,
			];

			echo view('commun/header', $data);
			echo view('Boutique/boutique', $data);
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
									->join('product_image', 'product.id_prod = product_image.id_prod')
									->join('image', 'image.id_img = product_image.id_img') 
									->where('product_category.id_cat', $category['id_cat'])
                                    ->where('product.on_sale', 't')
									->findAll();


			$data = [
				'pageTitle' => 'Boutique',
				'categories' => $categories,
				'category' => $category,
				'products' => $products
			];

			echo view('commun/header', $data);
			echo view('Boutique/categorie', $data);
			echo view('commun/footer');
		}

		public function produit($id_prod)
		{
			$categoryModel = new CategoryModel();
			$productModel = new ProductModel();

			$categories = $categoryModel->findAll();

			$product = $productModel->getProductById($id_prod);

			$relatedProducts = $productModel->getProductCategories($id_prod);
									
			$data = [
				'pageTitle' => 'Boutique',
				'categories' => $categories,
				'product' => $product,
				'relatedProducts' => $relatedProducts
			];


			echo view('commun/header', $data);
			echo view('Boutique/produit', $data);
			echo view('commun/footer');
		}
	}
?>