<?php
	namespace App\Controllers\Compte;
	use CodeIgniter\Controller;
	use App\Models\AccountModel;
	use App\Controllers\BaseController;

	class ForgotPasswordController extends BaseController
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
			$email     = $this->request->getPost('email');
			$accountModel = new AccountModel();
			$user      = $accountModel->where('email', $email)->first();

			// Dans la méthode sendResetLink du contrôleur ForgotPasswordController
			$email = $this->request->getPost('email');

			if ($user)
			{
				// Générer un jeton de réinitialisation de MDP et enregistrer-le dans BD
				$token = bin2hex(random_bytes(16));
				$expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));
				$accountModel->set('reset_token', $token)
				->set('reset_token_exp', $expiration)
				->update($user['id_user']);

				// Envoyer l'e-mail avec le lien de réinitialisation
				$resetLink = site_url("reset-password/$token");
				$message = $this->getEmail($resetLink);
				
				// Utilisez la classe Email de CodeIgniter pour envoyer l'e-mail
				$emailService = \Config\Services::email();
				
				//paramètres du mail
				$to = $this->request->getPost('to');
				$subject = $this->request->getPost('subject');
				$from = env('email_user', '');
				
				//envoi du mail
				$emailService->setTo($email);
				$emailService->setFrom($from, 'Maison Eau d\'Or');
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

			return redirect()->to('/forgot-password');
		}

		public function getEmail($resetLink)
		{
			return "<!DOCTYPE html>
					<html lang=\"fr\">
					<body style=\"margin: 0; padding: 0; background-color: #FFFFFF; font-family: Arial, sans-serif;\">
					<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"background-color: #FFFFFF; margin: 0; padding: 0;\">
						<tr>
						<td align=\"center\">
							<!-- Container -->
							<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border: 1px solid #ccc; background-color: #fff; padding: 20px;\">
							<!-- Header -->
							<tr>
								<td align=\"center\" style=\"padding: 10px 0;\">
								<a href=\"".site_url('/')."\">
									<img src=\"https://i.imgur.com/2Objlik.png\" alt=\"Logo\" width=\"200\" style=\"display: block;\">
								</a>
								</td>
							</tr>
							<!-- Navigation Menu -->
							<tr>
								<td align=\"center\" style=\"padding: 20px 0;\">
								<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"60%\">
									<tr>
										<td align=\"center\" style=\"padding: 5px;\">
											<a href=\"".site_url('/')."\" style=\"display: inline-block; text-decoration: none; color: #d4af37; font-size: 14px; font-weight: bold; border: 2px solid #d4af37; padding: 10px 20px; border-radius: 20px;\">Accueil</a>
										</td>
										<td align=\"center\" style=\"padding: 5px;\">
											<a href=\"".site_url('/boutique')."\" style=\"display: inline-block; text-decoration: none; color: #d4af37; font-size: 14px; font-weight: bold; border: 2px solid #d4af37; padding: 10px 20px; border-radius: 20px;\">Boutique</a>
										</td>
										<td align=\"center\" style=\"padding: 5px;\">
											<a href=\"". site_url('/blog') ."\" style=\"display: inline-block; text-decoration: none; color: #d4af37; font-size: 14px; font-weight: bold; border: 2px solid #d4af37; padding: 10px 20px; border-radius: 20px;\">Blog</a>
										</td>
									</tr>
								</table>
								</td>
							</tr>
							<!-- Content -->
							<tr>
								<td align=\"center\" style=\"padding: 20px;\">
								<h2 style=\"color: #000; font-size: 24px; margin: 0; text-align: left;\">Réinitialisation de mot de passe</h2>
								<p style=\"color: #333; font-size: 14px; line-height: 1.5; text-align: left;\">Bonjour,</p>
								<p style=\"color: #333; font-size: 14px; line-height: 1.5; text-align: left;\">Suite à votre demande, vous recevez ce mail pour réinitialiser votre mot de passe</p>
								<a href=\"".$resetLink."\" style=\"text-align: center; display: inline-block; background-color: #d4af37; color: #fff; text-decoration: none; padding: 10px 20px; font-size: 16px; border-radius: 20px; margin-top: 30px;\">Réinitialiser mon mot de passe</a>
								<p style=\"color: #333; font-size: 11px; line-height: 1.5; text-align: center; margin-top: 50px;\">Ce n'était pas vous ? Si vous n'êtes pas à l'origine de cette réinitialisation, nous vous invitons à <a href=\"".site_url('/forgot-password')."\">modifier votre mot de passe</a> dès que possible pour sécuriser votre compte.</p>
								</td>
							</tr>
							<!-- Footer -->
							<tr>
								<td align=\"center\" style=\"padding: 20px; background-color: #f7f7f7; margin-top: 50px;\">
								<p style=\"color: #333; font-size: 14px; margin: 0;\">Retrouvez-nous sur nos réseaux</p>
								<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top: 10px;\">
									<tr>
									<td style=\"padding: 0 5px;\">
										<a href=\"https://www.facebook.com/maisoneaudor76/\">
										<img src=\"https://eryasvi.stripocdn.email/content/assets/img/social-icons/logo-black/facebook-logo-black.png\" alt=\"Facebook\" width=\"32\" style=\"display: block;\">
										</a>
									</td>
									<td style=\"padding: 0 5px;\">
										<a href=\"https://www.instagram.com/maisoneaudor/\">
										<img src=\"https://eryasvi.stripocdn.email/content/assets/img/social-icons/logo-black/instagram-logo-black.png\" alt=\"Instagram\" width=\"32\" style=\"display: block;\">
										</a>
									</td>
									</tr>
								</table>
								</td>
							</tr>
							</table>
						</td>
						</tr>
					</table>
					</body>
					</html>
					";
		}
	}
?>