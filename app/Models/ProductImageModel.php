<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductImageModel extends Model
{
    protected $table = 'product_image';
    protected $allowedFields = ['id_prod', 'id_img'];

    // Désactiver la clé primaire automatique
    protected $primaryKey = false;
    protected $useAutoIncrement = false;

    /**
     * Méthode pour sauvegarder une relation produit-image.
     */
    public function saveComposite($data)
    {

        if (!isset($data['id_prod'], $data['id_img'])) {
            throw new \InvalidArgumentException('Les clés id_prod et id_img sont obligatoires.');
        }

        $exists = $this->where('id_prod', $data['id_prod'])
            ->where('id_img', $data['id_img'])
            ->countAllResults();

        if ($exists) {
            return true; // La relation existe déjà
        }

        return $this->db->table($this->table)->insert($data);
    }

    /**
     * Méthode pour supprimer une relation produit-image.
     */
    public function deleteComposite($id_prod, $id_img)
    {
        return $this->db->table($this->table)
            ->where('id_prod', $id_prod)
            ->where('id_img', $id_img)
            ->delete();
    }
}