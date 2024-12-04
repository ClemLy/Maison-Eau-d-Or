<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductCategoryModel extends Model
{
    protected $table = 'product_category';
    protected $allowedFields = ['id_prod', 'id_cat'];
}