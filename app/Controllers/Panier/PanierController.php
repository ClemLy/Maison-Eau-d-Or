<?php

	namespace App\Controllers\Panier;

	use App\Controllers\BaseController;
	use App\Models\CartModel;

	class PanierController extends BaseController
	{
		public function index()
		{
			$data = [
				'pageTitle' => 'Panier'
			];

			echo view('commun/header', $data);
			echo view('Panier/panier');
			echo view('commun/footer');
		}

		public function ajouter()
		{
			$id_prod = $this->request->getPost('id_prod');
			$quantity = $this->request->getPost('quantity');
	
			$id_user = session()->get('user_id'); 
	
			if (!$id_user) {
				return redirect()->to('/login'); 
			}
	
			$cartModel = new CartModel();
	
			$existingProduct = $cartModel->where('id_user', $id_user)
										 ->where('id_prod', $id_prod)
										 ->first();
	
			if ($existingProduct) 
			{
				
				$newQuantity = $existingProduct['quantity'] + $quantity;
	
				$cartModel->update($existingProduct['id_cart'], [
					'quantity' => $newQuantity
				]);
			} 
			else 
			{
				$cartModel->insert([
					'id_user'   => $id_user,
					'id_prod'   => $id_prod,
					'quantity'  => $quantity
				]);
			}
	
			
			// return redirect()->to('/panier'); 
		}
	}
?>