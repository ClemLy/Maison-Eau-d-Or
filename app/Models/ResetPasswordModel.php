<?php

	namespace App\Models;

	use CodeIgniter\Model;

	class ResetPasswordModel extends Model
	{
		protected $table = 'reset_password';
		protected $primaryKey = 'id_reset';
		protected $allowedFields = ['id_user', 'rst_tkn', 'rst_tkn_exp'];
	}
?>