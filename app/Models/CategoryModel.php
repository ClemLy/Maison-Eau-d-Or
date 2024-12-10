<?php
namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'id_cat';
    protected $allowedFields = ['cat_name'];

}