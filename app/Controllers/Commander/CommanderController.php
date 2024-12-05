<?php
namespace App\Controllers\Commander;

use App\Controllers\BaseController;
use App\Models\CartModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\AccountModel;
use setasign\Fpdi\Fpdi;

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

    public function generatePDF($orderId)
    {
        // Charger les données depuis la base de données
        //$orderModel = new OrderModel();
        //$order = $orderModel->getOrder($orderId);

        //if (!$order) {
        //    return redirect()->back()->with('error', 'Commande non trouvée.');
        //}

        // Chemin vers le modèle PDF
        $templatePath = FCPATH . 'assets/pdf/template_bon_commande.pdf';

        // Initialiser FPDI
        $pdf = new Fpdi();
        $pdf->AddPage();
        $pdf->setSourceFile($templatePath);
        $tplId = $pdf->importPage(1);
        $pdf->useTemplate($tplId);

        // Configurer les styles de texte
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 0);

        // Ajouter les informations de commande
        $pdf->SetXY(37, 83); // Position : Nom
        $pdf->Write(10, "KYLLIAN LE BRETON"); // $order['name']

        $pdf->SetXY(45, 92.5); // Position : Téléphone
        $pdf->Write(10, "0644125913"); // $order['phone']

        $pdf->SetXY(37, 101); // Position : Email
        $pdf->Write(10, "kyllian.lebreton27@gmail.com"); // $order['email']

        $pdf->SetXY(130, 55); // Position : Numéro de commande
        $pdf->Write(10, "E112412045460007751"); // $order['order_id']

        $pdf->SetXY(130, 33); // Position : Jour
        $pdf->Write(10, "15");

        $pdf->SetXY(153, 33); // Position : Jour
        $pdf->Write(10, "12");

        $pdf->SetXY(173, 33); // Position : Jour
        $pdf->Write(10, "2024");

        $pdf->SetXY(127, 83); // Position : Adresse Rue
        $pdf->Write(10, "12 rue de la Paix");
        
        $pdf->SetXY(127, 92.5); // Position : Adresse ZIP + VILLE
        $pdf->Write(10, "76600, Le Havre");

        $pdf->SetXY(127, 101); // Position : Adresse ZIP + VILLE
        $pdf->Write(10, "France");

        // Pour chaque produit dans la commande

        // Var pour la position Y
        $y = 130.5;

        $pdf->SetXY(23, $y); // Position : Total TTC
        $pdf->Write(10, 'Nuage Magique'); // $order['total']);

        $pdf->SetXY(23, 138); // Position : Total TTC
        $pdf->Write(10, 'Nuage Magique'); // $order['total']);


        // Générer le PDF
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('I', 'Bon_de_commande_' . $orderId . '.pdf');
    }
}
