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
			'newsletter',
			'activation_code', 
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