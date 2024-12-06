<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductImageModel extends Model
{
    protected $table = 'product_image'; // Nom de la table
    protected $primaryKey = '';
    protected $allowedFields = ['id_prod', 'id_img']; // Champs autorisés pour insertion/mise à jour

    public $timestamps = false; // Désactiver les timestamps si non utilisés

    // Définir les règles de validation
    protected $validationRules = [
        'id_prod' => 'required|numeric',
        'id_img'  => 'required|numeric',
    ];

    protected $validationMessages = [
        'id_prod' => [
            'required' => 'L\'ID du produit est obligatoire.',
            'numeric'  => 'L\'ID du produit doit être un nombre.',
        ],
        'id_img' => [
            'required' => 'L\'ID de l\'image est obligatoire.',
            'numeric'  => 'L\'ID de l\'image doit être un nombre.',
        ],
    ];

    // Méthode personnalisée pour insérer ou mettre à jour en fonction des clés primaires composites
    public function saveComposite(array $data)
    {
        $builder = $this->db->table($this->table);
        return $builder->insert($data);
    }
}