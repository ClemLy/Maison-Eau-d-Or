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
        $pdf->SetFont($fontName, '', 10); // Taille 12
        $pdf->SetTextColor(0, 0, 0);

        // Ajouter les informations de commande
        $pdf->SetXY(37, 83); // Position : Nom
        $pdf->Write(10, "KYLLIAN LE BRETON"); // $order['name']

        $pdf->SetXY(47, 92.5); // Position : Téléphone
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
        $width = 40; // Largeur de la zone

        $pdf->SetXY(23, $y); // Position : Total TTC
        $pdf->Write(10, 'Nuage Magique'); // $order['total']);

        $pdf->SetXY(117, $y+7.5*0); // Position : Total TTC
        $pdf->Cell($width, 10, '3', 0, 0, 'C'); // $order['total']);

        $pdf->SetXY(149, $y); // Position : Total TTC
        $pdf->Cell($width, 10, mb_convert_encoding('90 €', 'ISO-8859-15', 'UTF-8'), 0, 0, 'C');

        $pdf->SetXY(23, $y+7.5*1); // Position : Total TTC
        $pdf->Write(10, 'Falestine'); // $order['total']);

        $pdf->SetXY(117, $y+7.5*1); // Position : Total TTC
        $pdf->Cell($width, 10, '10', 0, 0, 'C'); // $order['total']);

        $pdf->SetXY(149, $y+7.5*1); // Position : Total TTC
        $pdf->Cell($width, 10, mb_convert_encoding('300 €', 'ISO-8859-15', 'UTF-8'), 0, 0, 'C');

        $pdf->SetXY(23, $y+7.5*2); // Position : Total TTC
        $pdf->Write(10, 'Gokuuuu'); // $order['total']);

        $pdf->SetXY(117, $y+7.5*2); // Position : Total TTC
        $pdf->Cell($width, 10, '2', 0, 0, 'C'); // $order['total']);;

        $pdf->SetXY(149, $y+7.5*2); // Position : Total TTC
        $pdf->Cell($width, 10, mb_convert_encoding('60 €', 'ISO-8859-15', 'UTF-8'), 0, 0, 'C');

        $pdf->SetXY(149, 209); // Position : Total TTC
        $pdf->Cell($width, 10, mb_convert_encoding('60 €', 'ISO-8859-15', 'UTF-8'), 0, 0, 'C');

        $pdf->SetXY(149, 216.5); // Position : Total TTC
        $pdf->Cell($width, 10, mb_convert_encoding('60 €', 'ISO-8859-15', 'UTF-8'), 0, 0, 'C');

        $pdf->SetFont('Times', '', 12);
        $pdf->SetXY(149, 225.5); // Position : Total TTC
        $pdf->Cell($width, 10, mb_convert_encoding('300 €', 'ISO-8859-15', 'UTF-8'), 0, 0, 'C');

        // Générer le PDF
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('I', 'Bon_de_commande_' . $orderId . '.pdf');
    }
}
