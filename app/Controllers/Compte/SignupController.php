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
				$email->setMessage('Cliquez sur ce lien pour activer votre compte : ' . site_url('activate/' . $data['activ_token']));
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
	}
?>