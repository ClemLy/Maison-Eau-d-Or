<?php

	namespace App\Controllers\Compte;

	use App\Models\AccountModel;
	use App\Controllers\BaseController;

	class CompteController extends BaseController
	{
		public function index()
		{
			if (!session()->get('isLoggedIn'))
			{
				return redirect()->to('/signin');
			}

			$data = [
				'pageTitle' => 'Compte'
			];

			echo view('commun/header', $data);
			echo view('Compte/account');
			echo view('commun/footer');
		}

		public function update()
		{
			if (!session()->get('isLoggedIn'))
			{
				return redirect()->to('/signin');
			}
	
			helper(['form']);
			$accountModel = new AccountModel();
			$userId = session()->get('id_user');
	
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
					'rules'  => 'required|valid_email',
					'errors' => [
						'required'    => 'Le champ Email est obligatoire.',
						'valid_email' => 'Le champ Email doit contenir une adresse email valide.'
					]
				],
				'phone_number' => [
					'rules'  => 'permit_empty|numeric|min_length[10]|max_length[10]',
					'errors' => [
						'numeric'    => 'Le numéro de téléphone doit contenir uniquement des chiffres.',
						'min_length' => 'Le numéro de téléphone contient 10 chiffres.',
						'max_length' => 'Le numéro de téléphone ne doit pas dépasser 10 chiffres.'
					]
				]
			];
	
			if ($this->validate($rules))
			{
				$updateData = [
					'last_name'    => $this->request->getPost('last_name'),
					'first_name'   => $this->request->getPost('first_name'),
					'email'        => $this->request->getPost('email'),
					'phone_number' => $this->request->getPost('phone_number'),
					'newsletter'   => $this->request->getPost('newsletter') ? true : false,
				];
		
				$accountModel->update($userId, $updateData);
		
				// Mettre à jour les données de session immédiatement
				session()->set($updateData);
	
				session()->setFlashdata('success', 'Informations modifiées avec succès.');
				return redirect()->to('/account');
			}
			else
			{
				$data = [
					'pageTitle'  => 'Modifier Mon Compte',
					'validation' => $this->validator
				];
				echo view('commun/header', $data);
				echo view('Compte/update');
				echo view('commun/footer');
			}
		}

		public function delete()
		{
			if (!session()->get('isLoggedIn'))
			{
				return redirect()->to('/signin');
			}
	
			$accountModel = new AccountModel();
			$userId = session()->get('id_user');
	
			$accountModel->delete($userId);
			session()->destroy();
	
			session()->setFlashdata('success', 'Compte supprimé avec succès.');
			return redirect()->to('/');
		}

		public function logout()
		{
			// Récupérer l'utilisateur connecté (s'il existe)
			$accountModel = new AccountModel();
			$userId = session()->get('id_user');
			
			// Supprimer le remember_token dans la base de données pour cet utilisateur
			if ($userId)
			{
				$accountModel->update($userId, ['remember_token' => null]);
			}

			// Supprimer la session
			session()->remove(['id_user', 'first_name', 'last_name', 'email', 'phone_number', 'isLoggedIn']);

			// Supprimer le cookie "remember_token"
			helper('cookie');
			delete_cookie('remember_cookie');

			// Rediriger vers la page de connexion
			echo "<script>window.location.href='/signin';</script>";
		}


		public function subscribe()
		{
			$userId       = session()->get('id_user'); 
			$accountModel = new AccountModel();

			// Appeler la méthode subscribe dans le modèle pour mettre à jour newsletter à true
			if ($accountModel->subscribe($userId))
			{
				session()->set(['newsletter' => true]);
				session()->setFlashdata('success', 'Vous êtes désormais inscrit à notre newsletter.');
			}
			else
			{
				session()->setFlashdata('error', 'Une erreur est survenue lors de l\'inscription à la newsletter.');
			}

			return redirect()->to('/');
		}
	}
?>