<?php
	namespace App\Controllers\Compte;
	use App\Models\UserModel;
	use App\Models\UserModelB;
	use CodeIgniter\Controller;
	use App\Controllers\BaseController;

	class ResetPasswordController extends Controller
	{
		public function index($token)
		{
			helper(['form']);

			$data = [
				'pageTitle' => 'Réinitialisation du Mot de Passe'
			];
		
			echo view('commun/header', $data);
			
			$userModel = new UserModelB();
			$user      = $userModel->where('reset_token', $token)

			->where('reset_token_exp >', date('Y-m-d H:i:s'))
			->first();

			if ($user)
			{
				echo view('Compte/reset_password', ['token' => $token]);
				echo view('commun/footer');
			}
			else
			{
				return 'Lien de réinitialisation non valide ou expiré.';
			}
		}

		public function updatePassword()
		{
			$token           = $this->request->getPost('token');
			$password        = $this->request->getPost('password');
			$confirmPassword = $this->request->getPost('confirm_password');
			
			// Vérification de la validité du token
			$userModelB = new UserModelB();
			$user = $userModelB->where('reset_token', $token)
							   ->where('reset_token_exp >', date('Y-m-d H:i:s'))
							   ->first();
		
			if ($user && $password === $confirmPassword)
			{
				// Hachage du nouveau mot de passe
				$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		
				// Mise à jour dans la table users via UserModel
				$userModel = new UserModel();
				$userModel->set('password', $hashedPassword) // Met à jour le mot de passe
						  ->where('email_user', $user['email_user']) // Utiliser l'email de l'utilisateur pour trouver l'enregistrement
						  ->update();
		
				// Mise à jour dans la table mdp via UserModelB
				$userModelB->set('password', $hashedPassword) // Met à jour le mot de passe
							->set('reset_token', null) // Invalider le token
							->set('reset_token_exp', null) // Réinitialiser l'expiration
							->where('email_user', $user['email_user']) // Utiliser l'email pour trouver l'enregistrement dans mdp
							->update();
		
				session()->setFlashdata('success-reset', 'Mot de passe réinitialisé avec succès.');
			}
			else
			{
				session()->setFlashdata('error-reset', 'Les mots de passe ne correspondent pas.');
			}

			echo view('Compte/reset_password', ['token' => $token]);
		}
	}
?>