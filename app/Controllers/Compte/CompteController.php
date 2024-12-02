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
				'pageTitle' => 'Mon Compte'
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
					'rules'  => 'required|valid_email|is_unique[users.email]',
					'errors' => [
						'required'    => 'Le champ Email est obligatoire.',
						'valid_email' => 'Le champ Email doit contenir une adresse email valide.',
						'is_unique'   => 'Cet email est déjà utilisé.'
					]
				]
			];
	
			if ($this->validate($rules))
			{
				$accountModel->update($userId, [
					'nom_user'    => $this->request->getPost('nom_user'),
					'prenom_user' => $this->request->getPost('prenom_user'),
					'email_user'  => $this->request->getPost('email_user')
				]);
	
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
			session()->remove(['id_user', 'first_name', 'last_name', 'email', 'isLoggedIn']);

			// Supprimer le cookie "remember_token"
			helper('cookie');
			delete_cookie('remember_cookie');

			// Rediriger vers la page de connexion
			echo "<script>window.location.href='/signin';</script>";
		}

	}
?>