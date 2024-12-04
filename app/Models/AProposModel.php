<?php
	namespace App\Models;
	use CodeIgniter\Model;

	class AProposModel extends Model
	{
		protected $table         = 'a_propos';
		protected $primaryKey    = 'id_apropos';
		protected $allowedFields = [
			'content'
		];

	}
?>