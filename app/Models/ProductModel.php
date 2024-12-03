<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'id_prod';

    protected $allowedFields = ['p_name', 'p_price', 'description', 'id_img', 'on_sale', 'is_star'];

    // Validation des données
    protected $validationRules = [
        'p_name'      => 'required|min_length[3]',
        'p_price'     => 'required|numeric',
        'description' => 'required|min_length[10]',
    ];

    // Messages d'erreur explicites
    protected $validationMessages = [
        'p_name' => [
            'required'   => 'Le nom du produit est obligatoire.',
            'min_length' => 'Le nom du produit doit contenir au moins 3 caractères.',
        ],
        'p_price' => [
            'required' => 'Le prix du produit est obligatoire.',
            'numeric'  => 'Le prix doit être un nombre valide.',
        ],
        'description' => [
            'required'   => 'La description est obligatoire.',
            'min_length' => 'La description doit contenir au moins 10 caractères.',
        ],
    ];

    public function getStarProduct()
    {
        return $this->select('product.*, image.img_path')
            ->join('image', 'product.id_img = image.id_img')
            ->where('product.is_star', true)
            ->first();
    }
}