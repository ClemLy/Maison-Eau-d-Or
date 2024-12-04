<?php

	namespace App\Controllers\Panier;

	use App\Controllers\BaseController;
	use App\Models\CartModel;
	use App\Models\ProductModel;

	class PanierController extends BaseController
	{
		public function panierGet()
		{
			$cartModel = new CartModel();
			$productModel = new ProductModel();

			$id_user = session()->get('id_user'); 

			if (!$id_user) 
			{
				return redirect()->to('/');
			}

			$cartItems = $cartModel
				->select('cart.id_prod, cart.quantity, product.p_name, product.p_price, image.img_path')
				->join('product', 'cart.id_prod = product.id_prod')
				->join('image', 'product.id_img = image.id_img')
				->where('cart.id_user', $id_user)
				->findAll();

			$data = [
				'pageTitle' => 'Panier',
				'cartItems' => $cartItems
			];

			echo view('commun/header', $data);
			echo view('Panier/panier', $data);
			echo view('commun/footer');
		}


		public function ajouter($id_prod)
		{
			$quantity = $this->request->getPost('quantity');
			$id_user = session()->get('id_user');

			if (!$id_user) 
			{
				return redirect()->to('/');
			}

			if ($quantity <= 0) 
			{
				return redirect()->back()->with('error', 'La quantité doit être supérieure à zéro.');
			}

			$cartModel = new CartModel();

			$existingProduct = $cartModel->where('id_user', $id_user)
										->where('id_prod', $id_prod)
										->first();

			if ($existingProduct) 
			{
				$newQuantity = $existingProduct['quantity'] + $quantity;
				$cartModel->update($existingProduct['id_cart'], ['quantity' => $newQuantity]);
			} 
			else 
			{
				$cartModel->insert([
					'id_user' => $id_user,
					'id_prod' => $id_prod,
					'quantity' => $quantity
				]);
			}

			return redirect()->to('/panier')->with('success', 'Produit ajouté au panier.');
		}

	}
?>