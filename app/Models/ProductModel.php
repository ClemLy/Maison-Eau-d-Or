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
            ->join('product_image', 'product.id_prod = product_image.id_prod') 
            ->join('image', 'product_image.id_img = image.id_img') 
            ->where('product.is_star', true)
            ->first();
    }

    public function getProducts()
    {
        // Récupérer les produits avec leurs données principales
        $products = $this->select('product.*')
            ->findAll();

        foreach ($products as &$product) {
            // Récupérer les catégories associées au produit
            $categories = $this->db->table('category')
                ->select('cat_name')
                ->join('product_category', 'category.id_cat = product_category.id_cat')
                ->where('product_category.id_prod', $product['id_prod'])
                ->get()
                ->getResultArray();

            $product['categories'] = $categories; // Associer les catégories au produit

            // Récupérer les images associées au produit
            $images = $this->db->table('image')
                ->select('image.img_path, image.img_name')
                ->join('product_image', 'product_image.id_img = image.id_img')
                ->where('product_image.id_prod', $product['id_prod'])
                ->get()
                ->getResultArray();

            $product['images'] = $images; // Associer les images au produit
        }

        return $products;
    }
    public function getProductById($id_prod)
    {
        // Récupérer le produit avec ses données principales
        $product = $this->select('product.*')
            ->where('product.id_prod', $id_prod)
            ->first();

        if ($product) {
            $db = \Config\Database::connect();

            // Récupérer les catégories associées au produit
            $categories = $db->table('category')
                ->select('category.cat_name')
                ->join('product_category', 'category.id_cat = product_category.id_cat')
                ->where('product_category.id_prod', $id_prod)
                ->get()
                ->getResultArray();

            $product['categories'] = array_column($categories, 'cat_name'); // Associer les catégories au produit

            // Récupérer les images associées au produit
            $images = $db->table('image')
                ->select('image.img_path, image.img_name,image.id_img')
                ->join('product_image', 'product_image.id_img = image.id_img')
                ->where('product_image.id_prod', $id_prod)
                ->get()
                ->getResultArray();

            $product['images'] = $images; // Associer les images au produit
        }

        return $product;
    }

}