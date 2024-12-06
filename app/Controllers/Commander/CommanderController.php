<?php
namespace App\Controllers\Commander;

use App\Controllers\BaseController;
use App\Models\CartModel;
use App\Models\OrderModel;
use App\Models\OrderProductModel;
use App\Models\AccountModel;
use App\Models\ProductModel;
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

    public function valider_commande()
    {
        $orderModel = new OrderModel();
        $orderProductModel = new OrderProductModel();
        $cartModel = new CartModel();

        $first_name = $this->request->getPost('first_name');
        $last_name = $this->request->getPost('last_name');
        $email = $this->request->getPost('email');
        $phone_number = $this->request->getPost('phone_number');
        $address_street = $this->request->getPost('address_street');
        $address_city = $this->request->getPost('address_city');
        $address_zip = $this->request->getPost('address_zip');
        $address_country = $this->request->getPost('address_country');
        $id_user = session()->get('id_user');

        $orderModel->insert([
            'id_user' => $id_user,
            'order_date' => date('Y-m-d H:i:s'),
            'phone_number_order' => $phone_number,
            'address_street' => $address_street,
            'address_city' => $address_city,
            'address_zip' => $address_zip,
            'address_country' => $address_country,
        ]);
        
        $userModel = new AccountModel();
        $user = $userModel->find($id_user);
        if ($user) 
        {
            $updateData = [];
            if ($first_name && $first_name !== $user['first_name']) 
            {
                $updateData['first_name'] = $first_name;
            }

            if ($last_name && $last_name !== $user['last_name']) 
            {
                $updateData['last_name'] = $last_name;
            }

            if ($email && $email !== $user['email']) 
            {
                $updateData['email'] = $email;
            }

            if ($phone_number && $phone_number !== $user['phone_number']) 
            {
                $updateData['phone_number'] = $phone_number;
            }
    
            if (!empty($updateData)) 
            {
                $userModel->update($id_user, $updateData);
            }
        }


        $id_order = $orderModel->getInsertID();

        $cartItems = $cartModel->where('id_user', $id_user)->findAll();

        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                $orderProductModel->insert([
                    'id_order' => $id_order,
                    'id_prod' => $item['id_prod'],
                    'quantity' => $item['quantity'],
                ]);
            }
    
            $cartModel->where('id_user', $id_user)->delete();
        }

        return redirect()->to('order/pdf/' . $id_order);
    }

    public function generatePDF($orderId)
    {
        // 1. URL de la police Montserrat
        $fontDir = FCPATH . 'assets/fonts/';
        $fontName = 'Montserrat-Regular';
        $fontPath = $fontDir . $fontName . '.ttf';

        // 4. Convertir la police en format FPDF
        $makefontPath = ROOTPATH . 'vendor/setasign/fpdf/makefont/makefont.php'; // Assurez-vous que makefont.php est installé
        $phpCommand = sprintf(
            'php %s %s ISO-8859-15',
            escapeshellarg($makefontPath),
            escapeshellarg($fontPath)
        );

        // 5. Déplacer les fichiers générés dans public/ vers le dossier FPDF font/
        $fontFiles = [
            ROOTPATH. 'public/' . $fontName . '.php',
            ROOTPATH. 'public/' . $fontName . '.z',
        ];

        $oldPath1 = ROOTPATH. 'public/' . $fontName . '.php';  // Chemin d'origine du fichier
        $oldPath2 = ROOTPATH. 'public/' . $fontName . '.z';  // Chemin d'origine du fichier

        $newPath1 = ROOTPATH. 'vendor/setasign/fpdf/font/' . $fontName . '.php';  // Nouveau chemin du fichier
        $newPath2 = ROOTPATH. 'vendor/setasign/fpdf/font/' . $fontName . '.z';  // Nouveau chemin du fichier

        rename($oldPath1, $newPath1);  // Déplacer le fichier
        rename($oldPath2, $newPath2);  // Déplacer le fichier

        // Exécuter la commande pour générer les fichiers nécessaires
        exec($phpCommand, $output, $resultCode);
        if ($resultCode !== 0) {
            die('Erreur lors de la conversion de la police : ' . implode("\n", $output));
        }

        $orderModel = new OrderModel();
        $order = $orderModel
                            ->select('orders.id_order, orders.order_date, orders.phone_number_order, orders.address_street, orders.address_city, 
                            orders.address_zip, orders.address_country, users.first_name, users.last_name, users.email, users.phone_number AS user_phone_number') 
                            ->join('users', 'users.id_user = orders.id_user') 
                            ->where('orders.id_order', $orderId) 
                            ->first();
        
        $productModel = new ProductModel();
        $products = $productModel
                            ->select('product.p_name, product.p_price, order_product.quantity')
                            ->join('order_product', 'order_product.id_prod = product.id_prod')
                            ->where('order_product.id_order', $orderId)
                            ->findAll();


        // Chemin vers le modèle PDF
        $templatePath = FCPATH . 'assets/pdf/template_bon_commande.pdf';

        // Initialiser FPDI
        $pdf = new Fpdi();
        $pdf->AddPage();
        $pdf->setSourceFile($templatePath);
        $tplId = $pdf->importPage(1);
        $pdf->useTemplate($tplId);

        // Configurer les styles de texte
        $pdf->AddFont($fontName, '', $fontName . '.php');
        $pdf->SetFont($fontName, '', 10);
        $pdf->SetTextColor(0, 0, 0);

        // Ajouter les informations de commande
        $pdf->SetXY(37, 83); // Position : Nom
        $pdf->Write(10, $order['last_name'] . " " . $order['first_name']); 

        $pdf->SetXY(47, 92.5); // Position : Téléphone
        $pdf->Write(10, $order['phone_number_order']); 

        $pdf->SetXY(37, 101); // Position : Email
        $pdf->Write(10, $order['email']); 

        $pdf->SetXY(130, 55); // Position : Numéro de commande
        $pdf->Write(10, $order['id_order']);

        $orderDate = strtotime($order['order_date']); 
        $pdf->SetXY(130, 33); // Position : Jour
        $pdf->Write(10, date('d', $orderDate)); // Jour
        
        $pdf->SetXY(153, 33); // Position : Mois
        $pdf->Write(10, date('m', $orderDate)); // Moisid_imgPays
        $pdf->Write(10, $order['address_country']); // $order['address_country']

        // Var pour la position Y
        $y = 130.5;
        $width = 40; // Largeur de la zone

        $totalOrder = 0;
        foreach ($products as $product) {
            // Afficher le nom du produit
            $pdf->SetXY(23, $y); // Position : Nom du produit
            $pdf->Write(10, $product['p_name']); // Afficher le nom du produit
        
            // Afficher la quantité
            $pdf->SetXY(117, $y); // Position : Quantité
            $pdf->Cell($width, 10, $product['quantity'], 0, 0, 'C'); // Afficher la quantité
        
            // Afficher le prix total (prix unitaire * quantité)
            $pdf->SetXY(149, $y); // Position : Prix total
            $totalPrice = $product['p_price'] * $product['quantity']; // Calcul du prix total pour ce produit
            $pdf->Cell($width, 10, mb_convert_encoding(number_format($totalPrice, 2, ',', ' ') . ' €', 'ISO-8859-15', 'UTF-8'), 0, 0, 'C');
        
            // Passer à la ligne suivante
            $y += 7.5; // Modifier la valeur en fonction de l'espacement désiré


            $totalPrice = $product['p_price'] * $product['quantity']; // Calcul du prix total pour ce produit
            $totalOrder += $totalPrice; 
        }

        
        $pdf->SetXY(149, 209); // Position : Total TTC
        $pdf->Cell($width, 10, mb_convert_encoding($totalOrder *0.8. ' €', 'ISO-8859-15', 'UTF-8'), 0, 0, 'C');

        $pdf->SetXY(149, 216.5); // Position : Total TTC
        $pdf->Cell($width, 10, mb_convert_encoding($totalOrder *0.2. ' €', 'ISO-8859-15', 'UTF-8'), 0, 0, 'C');

        $pdf->SetXY(149, 225.5); // Position : Total TTC
        $pdf->Cell($width, 10, mb_convert_encoding($totalOrder. ' €', 'ISO-8859-15', 'UTF-8'), 0, 0, 'C');

        // Générer le PDF
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('I', 'Bon_de_commande_' . $orderId . '.pdf');
    }
}
