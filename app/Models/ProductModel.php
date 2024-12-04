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
        'p_name'      => 'required',
        'p_price'     => 'required|numeric',
        'description' => 'required',
        'id_img'      => 'required|numeric',
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
        'id_img' => [
            'required' => 'L\'image du produit est obligatoire.',
            'numeric'  => 'L\'image du produit est invalide.',
        ],
    ];

    public function getStarProduct()
    {
        return $this->select('product.*, image.img_path')
            ->join('image', 'product.id_img = image.id_img')
            ->where('product.is_star', true)
            ->first();
    }
    public function getProducts()
    {
        $products = $this->select('
                product.*, 
                image.img_path, 
                image.img_name
            ')
            ->join('image', 'product.id_img = image.id_img', 'left')
            ->findAll();

        foreach ($products as &$product) {
            $categories = $this->db->table('category')
                ->select('cat_name')
                ->join('product_category', 'category.id_cat = product_category.id_cat')
                ->where('product_category.id_prod', $product['id_prod'])
                ->get()
                ->getResultArray();

            $product['categories'] = $categories; // Associer les catégories au produit
        }

        return $products;
    }
    public function getProductById($id_prod)
    {
        $product = $this->select('
                product.*, 
                image.img_path, 
                image.img_name
            ')
            ->join('image', 'product.id_img = image.id_img', 'left')
            ->where('product.id_prod', $id_prod)
            ->first();

        if ($product) {
            // Récupérer les catégories associées au produit
            $db = \Config\Database::connect();
            $categories = $db->table('product_category')
                ->select('category.cat_name')
                ->join('category', 'product_category.id_cat = category.id_cat')
                ->where('product_category.id_prod', $id_prod)
                ->get()
                ->getResultArray();

            $product['categories'] = array_column($categories, 'cat_name');
        }

        return $product;
    }

}