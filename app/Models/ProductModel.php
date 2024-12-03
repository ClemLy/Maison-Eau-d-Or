<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table      = 'product';
    protected $primaryKey = 'id_prod';
    protected $allowedFields = [
        'p_name', 
        'p_price', 
        'description', 
        'id_img', 
        'on_sale', 
        'is_star'
    ];

    public function getStarProduct()
    {
        return $this->select('product.*, image.img_path')
                    ->join('image', 'product.id_img = image.id_img') 
                    ->where('product.is_star', true) 
                    ->first();
    }

}