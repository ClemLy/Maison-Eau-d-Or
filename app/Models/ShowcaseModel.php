<?php
	namespace App\Models;
	use CodeIgniter\Model;

	class ShowcaseModel extends Model
	{
		protected $table         = 'showcase';
		protected $primaryKey    = 'id_show';
		protected $allowedFields = [
			'id_show',
			'id_cat'
		];


		public function truncateShowcase()
		{
			$this->db->query("TRUNCATE TABLE {$this->table}");
		}
	
		/**
		 * Insérer plusieurs catégories dans SHOWCASE en une seule requête
		 */
		public function insertCategoriesBatch(array $categories)
		{
			return $this->insertBatch($categories); // Utilise la méthode native insertBatch
		}
	}

?>