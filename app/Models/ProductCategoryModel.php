<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductCategoryModel extends Model
{
    protected $table = 'product_category';
    protected $allowedFields = ['id_prod', 'id_cat'];
    protected $primaryKey = ''; // Pas de clé primaire unique gérée automatiquement

    public function saveComposite(array $data)
    {
        $builder = $this->db->table($this->table);
        return $builder->insert($data);
    }
}

