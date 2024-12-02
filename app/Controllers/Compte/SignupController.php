<?php
	namespace App\Controllers\Compte;
	use App\Models\UserModel;
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
				'nom_user' => [
					'rules'  => 'required|max_length[50]',
					'errors' => [
						'required'   => 'Le champ Nom est obligatoire.',
						'max_length' => 'Le Nom ne doit pas dépasser 50 caractères.'
					]
				],
				'prenom_user' => [
					'rules'  => 'required|max_length[50]',
					'errors' => [
						'required'   => 'Le champ Prénom est obligatoire.',
						'max_length' => 'Le Prénom ne doit pas dépasser 50 caractères.'
					]
				],
				'email_user' => [
					'rules'  => 'required|valid_email',
					'errors' => [
						'required'    => 'Le champ Email est obligatoire.',
						'valid_email' => 'Le champ Email doit contenir une adresse email valide.'
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
				$userModel = new UserModel();

				$data = [
					'nom_user'        => $this->request->getVar('nom_user'),
					'prenom_user'     => $this->request->getVar('prenom_user'),
					'email_user'      => $this->request->getVar('email_user'),
					'password'        => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
					'activation_code' => bin2hex(random_bytes(16))
				];

				$userModel->save($data);
				$from = env('email_user', '');

				// Envoyer l'email
				$email = \Config\Services::email();
				$email->setFrom($from, 'TaskPlanner');
				$email->setTo($data['email_user']);
				$email->setSubject('Activation du compte');
				$email->setMessage('Cliquez sur ce lien pour activer votre compte : ' . site_url('activate/' . $data['activation_code']));
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
			$userModel = new UserModel();

			// Recherche l'utilisateur avec le code d'activation
			$user = $userModel->where('activation_code', $activation_code)->first();

			if ($user)
			{
				// Mettre à jour l'utilisateur comme vérifié
				$userModel->set('is_verified', true)
						  ->set('activation_code', null) // Supprime le code d'activation
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