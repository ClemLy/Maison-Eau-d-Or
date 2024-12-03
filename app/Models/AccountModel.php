<?php
	namespace App\Models;
	use CodeIgniter\Model;

	class AccountModel extends Model
	{
		protected $table         = 'users';
		protected $primaryKey    = 'id_user';
		protected $allowedFields = [
			'first_name',
			'last_name',
			'email',
			'password',
			'phone_number',
			'newsletter',
			'reset_token',
			'reset_token_exp',
			'activ_token', 
			'activ_exp',
			'is_verified',
			'remember_token',
			'is_admin'
		];

		public function getUserByEmail($email)
		{
			return $this->where('email', $email)->first();
		}
	}
?>