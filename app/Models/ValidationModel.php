<?php

	namespace App\Models;

	use CodeIgniter\Model;

	class ValidationModel extends Model
	{
		protected $table         = 'validation';
		protected $primaryKey    = 'id_validation';
		protected $allowedFields = ['id_user', 'valid_exp'];
	}
?>