<?php
	namespace App\Controllers\Compte;
	use CodeIgniter\Controller;
	use App\Models\UserModelB;
	use App\Controllers\BaseController;

	class ForgotPasswordController extends Controller
	{
		public function index()
		{
			helper(['form']);

			$data = [
				'pageTitle' => 'Mot de Passe Oublié'
			];
		
			echo view('commun/header', $data);
			echo view('Compte/forgot_password');
			echo view('commun/footer');
		}

		public function sendResetLink()
		{
			$email = $this->request->getPost('email_user');
			$userModel = new UserModelB();
			$user = $userModel->where('email_user', $email)->first();

			// Dans la méthode sendResetLink du contrôleur ForgotPasswordController
			$email = $this->request->getPost('email_user');

			if ($user)
			{
				// Générer un jeton de réinitialisation de MDP et enregistrer-le dans BD
				$token = bin2hex(random_bytes(16));
				$expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));
				$userModel->set('reset_token', $token)
				->set('reset_token_exp', $expiration)
				->update($user['id_user']);

				// Envoyer l'e-mail avec le lien de réinitialisation
				$resetLink = site_url("reset-password/$token");
				$message = "Cliquez sur le lien suivant pour réinitialiser votre mot de passe : $resetLink";
				
				// Utilisez la classe Email de CodeIgniter pour envoyer l'e-mail
				$emailService = \Config\Services::email();
				
				//paramètres du mail
				$to = $this->request->getPost('to');
				$subject = $this->request->getPost('subject');
				$from = env('email_user', '');
				
				//envoi du mail
				$emailService->setTo($email);
				$emailService->setFrom($from, 'TaskPlanner');
				$emailService->setSubject('Réinitialisation de mot de passe');
				$emailService->setMessage($message);
				
				if ($emailService->send())
				{
					session()->setFlashdata('success-password', 'E-mail envoyé avec succès. Veuillez vérifier votre boîte de réception.');
				}
				else
				{
					session()->setFlashdata('error-password', 'Une erreur est survenue lors de l\'envoi de l\'e-mail.');
				}
			}
			else
			{
				session()->setFlashdata('error-password', 'Adresse e-mail non valide.');
			}

			echo view('Compte/forgot_password');
		}
	}
?>