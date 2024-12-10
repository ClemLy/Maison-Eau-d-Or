<?php
	namespace App\Controllers\Compte;

	use App\Models\AccountModel;
	use App\Controllers\BaseController;

	class SignupController extends BaseController
	{
		public function index()
		{
			helper(['form']);

			$data = [
				'pageTitle' => 'Inscription'
			];
		
			echo view('commun/header', $data);
			echo view('Compte/signup');
			echo view('commun/footer');
		}

		public function store()
		{
			helper(['form']);

			$rules = [
				'first_name' => [
					'rules'  => 'required|max_length[50]',
					'errors' => [
						'required'   => 'Le champ Prénom est obligatoire.',
						'max_length' => 'Le Prénom ne doit pas dépasser 50 caractères.'
					]
				],
				'last_name' => [
					'rules'  => 'required|max_length[50]',
					'errors' => [
						'required'   => 'Le champ Nom est obligatoire.',
						'max_length' => 'Le Nom ne doit pas dépasser 50 caractères.'
					]
				],
				'email' => [
					'rules'  => 'required|valid_email|is_unique[users.email]',
					'errors' => [
						'required'    => 'Le champ Email est obligatoire.',
						'valid_email' => 'Le champ Email doit contenir une adresse email valide.',
						'is_unique'   => 'Cet email est déjà utilisé.'
					]
				],
				'phone_number' => [
					'rules'  => 'permit_empty|numeric|min_length[10]|max_length[10]',
					'errors' => [
						'numeric'    => 'Le numéro de téléphone doit contenir uniquement des chiffres.',
						'min_length' => 'Le numéro de téléphone contient 10 chiffres.',
						'max_length' => 'Le numéro de téléphone ne doit pas dépasser 10 chiffres.'
					]
				],
				'password' => [
					'rules'  => 'required|min_length[4]',
					'errors' => [
						'required'  => 'Le champ Mot de passe est obligatoire.',
						'min_length' => 'Le Mot de passe doit comporter au moins 4 caractères.'
					]
				],
				'confirmpassword' => [
					'rules'  => 'required|matches[password]',
					'errors' => [
						'required' => 'Le champ Confirmation du mot de passe est obligatoire.',
						'matches'  => 'Le champ Confirmation ne coïncide pas avec le mot de passe.'
					]
				]
			];

			if ($this->validate($rules))
			{
				$accountModel    = new AccountModel();
				$expiration      = date('Y-m-d H:i:s', strtotime('+1 hour'));

				// Insérer les informations de l'utilisateur
				$data = [
					'first_name'    => $this->request->getVar('first_name'),
					'last_name'     => $this->request->getVar('last_name'),
					'email'         => $this->request->getVar('email'),
					'password'      => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
					'phone_number'  => $this->request->getVar('phone_number'),
					'newsletter'    => $this->request->getPost('newsletter') ? true : false,
					'activ_token'   => bin2hex(random_bytes(16)),
					'activ_exp'     => date('Y-m-d H:i:s', strtotime('+1 day'))
				];

				$accountModel->save($data);

				// Envoi de l'email d'activation
				$email = \Config\Services::email();
				$email->setFrom(env('email_user', ''), 'Maison Eau d\'Or');
				$email->setTo($data['email']);
				$email->setSubject('Activation de votre compte');
				$email->setMessage($this->getEmail($data));
				$email->send();

				return redirect()->to('/signin')->with('msg', 'Un lien d\'activation a été envoyé à votre adresse email. Veuillez vérifier votre boîte mail.');
			}
			else
			{
				$data['validation'] = $this->validator;
				echo view('Compte/signup', $data);
			}
		}

		public function activate($activation_code)
		{
			$accountModel    = new AccountModel();

			// Recherche l'utilisateur avec le code d'activation
			$user = $accountModel->where('activ_token', $activation_code)->first();

			if ($user)
			{
				// Mettre à jour l'utilisateur comme vérifié
				$accountModel->set('is_verified', true)
						->set('activ_token', null) // Supprime le code d'activation
						->where('id_user', $user['id_user'])
						->update();

				return redirect()->to('/signin')->with('success', 'Votre compte a été activé avec succès.');
			}
			else
			{
				return redirect()->to('/signup')->with('error', 'Lien d\'activation invalide ou expiré.');
			}
		}

		public function getEmail($data)
		{
			return " <!DOCTYPE html>
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
						<td align=\"center\" style=\"text-align: center; padding: 20px;\">
						  <h2 style=\"color: #000; font-size: 24px; margin: 0;\">Confirmez votre compte</h2>
						  <p style=\"color: #333; font-size: 16px; line-height: 1.5;\">Afin de valider votre inscription, veuillez cliquer sur le lien ci-dessous.</p>
						  <a href=\"". site_url('activate/' . $data['activ_token'])."\" style=\"display: inline-block; background-color: #d4af37; color: #fff; text-decoration: none; padding: 10px 20px; font-size: 16px; border-radius: 20px;\">Confirmez mon compte</a>
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
			</html>";
		}
	}
?>