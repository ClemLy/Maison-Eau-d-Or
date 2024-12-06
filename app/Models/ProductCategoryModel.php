<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductCategoryModel extends Model
{
    protected $table = 'product_category';
    protected $allowedFields = ['id_prod', 'id_cat'];

    // Désactiver la gestion automatique des clés primaires
    protected $primaryKey = false;
    protected $useAutoIncrement = false;

    /**
     * Sauvegarder une relation produit-catégorie.
     */
    public function saveComposite($data)
    {
        if (!isset($data['id_prod'], $data['id_cat'])) {
            throw new \InvalidArgumentException('Les clés id_prod et id_cat sont obligatoires.');
        }

        $exists = $this->where('id_prod', $data['id_prod'])
            ->where('id_cat', $data['id_cat'])
            ->countAllResults();

        if ($exists) {
            return true; // La relation existe déjà
        }

        return $this->db->table($this->table)->insert($data);
    }

    /**
     * Supprimer une relation produit-catégorie.
     */
    public function deleteComposite($id_prod, $id_cat)
    {
        return $this->db->table($this->table)
            ->where('id_prod', $id_prod)
            ->where('id_cat', $id_cat)
            ->delete();
    }
}


