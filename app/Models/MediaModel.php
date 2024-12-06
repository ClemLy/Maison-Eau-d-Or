<?php

namespace App\Models;

use CodeIgniter\Model;

class MediaModel extends Model
{
    protected $table = 'image'; // Nom de la table
    protected $primaryKey = 'id_img'; // Clé primaire
    protected $allowedFields = ['img_name', 'img_path']; // Colonnes modifiables

    // Méthode pour trouver une image par son nom
    public function getImagesByProductId($id_prod)
    {

        // Requête pour récupérer les images associées au produit
        return $this->select('image.id_img, image.img_path, image.img_name')
            ->join('product_image', 'product_image.id_img = image.id_img')
            ->where('product_image.id_prod', $id_prod)
            ->findAll();
    }
}