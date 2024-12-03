<?php

namespace App\Models;

use CodeIgniter\Model;

class MediaModel extends Model
{
    protected $table = 'image'; // Nom de la table
    protected $primaryKey = 'id_img'; // Clé primaire
    protected $allowedFields = ['img_name', 'img_path']; // Colonnes modifiables

    // Méthode pour trouver une image par son nom
    public function findByName($name)
    {
        return $this->where('img_name', $name)->first();
    }
}