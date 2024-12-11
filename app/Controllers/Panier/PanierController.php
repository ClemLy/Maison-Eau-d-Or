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
								->join('product_image', 'product.id_prod = product_image.id_prod')
								->join('image', 'product_image.id_img = image.id_img')
                                ->where('product.on_sale', 't')
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


		// public function ajouter($id_prod)
		// {
		// 	$quantity = $this->request->getPost('quantity');
		// 	$id_user = session()->get('id_user');

		// 	if (!$id_user) 
		// 	{
		// 		return redirect()->to('/');
		// 	}

		// 	if ($quantity <= 0) 
		// 	{
		// 		return redirect()->back()->with('error', 'La quantité doit être supérieure à zéro.');
		// 	}

		// 	$cartModel = new CartModel();

		// 	$existingProduct = $cartModel->where('id_user', $id_user)
		// 								->where('id_prod', $id_prod)
		// 								->first();

		// 	if ($existingProduct) 
		// 	{
		// 		$newQuantity = $existingProduct['quantity'] + $quantity;
		// 		$cartModel->update($existingProduct['id_cart'], ['quantity' => $newQuantity]);
		// 	} 
		// 	else 
		// 	{
		// 		$cartModel->insert([
		// 			'id_user' => $id_user,
		// 			'id_prod' => $id_prod,
		// 			'quantity' => $quantity
		// 		]);
		// 	}

		// 	// return redirect()->to('')->with('success', 'Produit ajouté au panier.');
		// 	return redirect()->back()->with('success', 'Produit ajouté au panier.');
		// }


		public function ajouter($id_prod)
		{
			$quantity = $this->request->getPost('quantity');
			$id_user = session()->get('id_user');

			if (!$id_user) {
				return $this->response->setJSON([
					'success' => false,
					'message' => 'Vous devez être connecté pour ajouter des produits au panier.'
				]);
			}

			if ($quantity <= 0) {
				return $this->response->setJSON([
					'success' => false,
					'message' => 'La quantité doit être supérieure à zéro.'
				]);
			}

			// Ajouter ou mettre à jour le produit dans le panier
			$cartModel = new CartModel();
			$existingProduct = $cartModel->where('id_user', $id_user)
										->where('id_prod', $id_prod)
										->first();

			if ($existingProduct) {
				$newQuantity = $existingProduct['quantity'] + $quantity;
				$cartModel->update($existingProduct['id_cart'], ['quantity' => $newQuantity]);
			} else {
				$cartModel->insert([
					'id_user' => $id_user,
					'id_prod' => $id_prod,
					'quantity' => $quantity
				]);
			}

			return $this->response->setJSON([
				'success' => true,
				'message' => 'Produit ajouté au panier.'
			]);
		}

		public function actualiser()
		{
			$id_user = session()->get('id_user');

			if (!$id_user) {
				return $this->response->setJSON([
					'success' => false,
					'message' => 'Utilisateur non connecté.'
				]);
			}

			// Récupérer les articles du panier de l'utilisateur
			$cartModel = new CartModel();
			$cartItems = $cartModel->select('cart.id_cart, product.p_name, product.p_price, cart.quantity, product.id_prod, image.img_path')
								->join('product', 'product.id_prod = cart.id_prod')
								->join('product_image', 'product_image.id_prod = product.id_prod')
								->join('image', 'image.id_img = product_image.id_img')
								->where('cart.id_user', $id_user)
								->findAll();

			// Retourner les articles du panier en réponse JSON
			return $this->response->setJSON([
				'success' => true,
				'cartItems' => $cartItems
			]);

		}
		public function supprimer($id_prod)
		{
			$id_user = session()->get('id_user');

			// Vérification de la connexion de l'utilisateur
			if (!$id_user) {
				return redirect()->to('/connexion');  // Redirige l'utilisateur vers la page de connexion
			}

			$cartModel = new CartModel();
			
			// Supprimer l'élément du panier
			$cartModel->where('id_user', $id_user)
					->where('id_prod', $id_prod)
					->delete();

			// Récupérer les éléments mis à jour du panier
			$cartItems = $cartModel->select('cart.id_cart, product.p_name, product.p_price, cart.quantity, product.id_prod, image.img_path')
								->join('product', 'product.id_prod = cart.id_prod')
								->join('product_image', 'product_image.id_prod = product.id_prod')
								->join('image', 'image.id_img = product_image.id_img')
								->where('cart.id_user', $id_user)
								->findAll();

			// Retourner les éléments du panier mis à jour en JSON
			return $this->response->setJSON([
				'success' => true,
				'cartItems' => $cartItems, // Les produits du panier mis à jour
				'message' => 'Produit supprimé du panier.'
			]);
		}




		// public function supprimer($id_prod)
		// {
		// 	$cartModel = new CartModel();

		// 	$id_user = session()->get('id_user'); 
		// 	if (!$id_user) 
		// 	{
		// 		return redirect()->to('/');
		// 	}

		// 	$cartModel->where('id_user', $id_user)
		// 			->where('id_prod', $id_prod)
		// 			->delete();

		// 	return redirect()->to('/panier');
		// }
		public function vider()
		{
			$cartModel = new CartModel();
			$id_user = session()->get('id_user');

			if (!$id_user) 
			{
				return redirect()->to('/');
			}

			$cartModel->where('id_user', $id_user)->delete();

			return redirect()->to('/panier');
		}


		public function modifier($id_prod)
		{
			$cartModel = new CartModel();
			$id_user = session()->get('id_user');
			$quantity = $this->request->getPost('quantity');

			if (!$id_user) 
			{
				return redirect()->to('/');
			}

			if (empty($quantity)) {
				return redirect()->to('/panier')->with('danger', 'La quantité doit etre indiqué');
			}

			$cartModel->where('id_user', $id_user)
					  ->where('id_prod', $id_prod)
					  ->set('quantity',$quantity)
					  ->update();

			return redirect()->to('/panier');
		}


	}
?>