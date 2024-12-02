<?php
	namespace App\Controllers\Compte;
	use CodeIgniter\Controller;
	use App\Models\UserModel;
	use App\Controllers\BaseController;

	class LogoutController extends Controller
	{
		public function logout()
		{
			// Récupérer l'utilisateur connecté (s'il existe)
			$userModel = new UserModel();
			$userId = session()->get('id_user');
			
			// Supprimer le remember_token dans la base de données pour cet utilisateur
			if ($userId)
			{
				$userModel->update($userId, ['remember_token' => null]);
			}

			// Supprimer la session
			session()->remove(['id_user', 'nom_user', 'prenom_user', 'email_user', 'isLoggedIn']);

			// Supprimer le cookie "remember_token"
			helper('cookie');
			delete_cookie('remember_cookie');

			// Rediriger vers la page de connexion
			echo "<script>window.location.href='/signin';</script>";
		}
	}
?>