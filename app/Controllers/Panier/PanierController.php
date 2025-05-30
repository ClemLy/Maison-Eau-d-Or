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

			$cartModel = new CartModel();
			$cartItems = $cartModel->select('cart.id_cart, product.p_name, product.p_price, cart.quantity, product.id_prod, image.img_path')
								->join('product', 'product.id_prod = cart.id_prod')
								->join('product_image', 'product_image.id_prod = product.id_prod')
								->join('image', 'image.id_img = product_image.id_img')
								->where('cart.id_user', $id_user)
								->findAll();

			return $this->response->setJSON([
				'success' => true,
				'cartItems' => $cartItems
			]);

		}
		public function supprimer($id_prod)
		{
			$id_user = session()->get('id_user');

			if (!$id_user) {
				return redirect()->to('/connexion'); 
			}

			$cartModel = new CartModel();
			
			$cartModel->where('id_user', $id_user)
					->where('id_prod', $id_prod)
					->delete();

			$cartItems = $cartModel->select('cart.id_cart, product.p_name, product.p_price, cart.quantity, product.id_prod, image.img_path')
								->join('product', 'product.id_prod = cart.id_prod')
								->join('product_image', 'product_image.id_prod = product.id_prod')
								->join('image', 'image.id_img = product_image.id_img')
								->where('cart.id_user', $id_user)
								->findAll();

			return $this->response->setJSON([
				'success' => true,
				'cartItems' => $cartItems,
				'message' => 'Produit supprimé du panier.'
			]);
		}

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

		public function getCartItemCount()
		{
			$id_user = session()->get('id_user');
			$cartModel = new \App\Models\CartModel();
		
			$cartItems = $cartModel
				->select('quantity')
				->where('id_user', $id_user)
				->findAll();
			
			$totalItems = array_sum(array_column($cartItems, 'quantity'));
		
			return $this->response->setJSON(['totalItems' => $totalItems]);
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

		public function modifierSideMenu()
		{
			$cartModel = new CartModel();
			$id_user = session()->get('id_user');
		
			// Récupère les données JSON
			$data = $this->request->getJSON(true);
			$id_prod = $data['id_prod'] ?? null;
			$quantity = $data['quantity'] ?? null;
		
			// Logs pour debug
			log_message('info', 'Données reçues : ' . json_encode($data));
			log_message('info', 'ID Produit : ' . $id_prod . ', Quantité : ' . $quantity);
		
			// Vérifie si l'utilisateur est authentifié
			if (!$id_user) {
				return $this->response->setJSON([
					'success' => false,
					'message' => 'Utilisateur non authentifié.',
				]);
			}
		
			// Vérifie que les données nécessaires sont fournies
			if (empty($id_prod) || empty($quantity)) {
				return $this->response->setJSON([
					'success' => false,
					'message' => 'Produit ou quantité non spécifiés.',
				]);
			}
		
			// Valide la quantité
			if ($quantity < 1 || $quantity > 99) {
				return $this->response->setJSON([
					'success' => false,
					'message' => 'Quantité invalide.',
				]);
			}
		
			// Met à jour le panier
			$cartModel->where('id_user', $id_user)
					  ->where('id_prod', $id_prod)
					  ->set('quantity', $quantity)
					  ->update();
		
			return $this->response->setJSON([
				'success' => true,
				'message' => 'Quantité mise à jour.',
			]);
		}
		
		

	}

	
?>

