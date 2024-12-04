<?php
namespace App\Controllers\Commander;

use App\Controllers\BaseController;
use App\Models\CartModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\AccountModel;

class CommanderController extends BaseController
{
    public function index()
    {
        $cartModel = new CartModel();
        $userModel = new AccountModel();

        $id_user = session()->get('id_user');

        if (!$id_user) 
        {
            return redirect()->to('/');
        }

        $user = $userModel->find($id_user);

        $cartItems = $cartModel->join('product', 'cart.id_prod = product.id_prod')
            ->where('cart.id_user', $id_user)
            ->findAll();

        if (empty($cartItems)) 
        {
            return redirect()->to('/panier')->with('info', 'Votre panier est vide. Ajoutez des produits pour passer une commande.');
        }

        // Calculer le total de la commande
        $total = array_reduce($cartItems, function($sum, $item) {
            return $sum + $item['p_price'] * $item['quantity'];
        }, 0);

        $data = [
            'pageTitle' => 'Passer la commande',
            'user' => $user, 
            'total' => $total, 
        ];
        echo view('commun/header', $data);
        echo view('Commander/commander', $data);
        echo view('commun/footer');
    }
}
