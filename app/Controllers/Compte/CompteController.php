<?php

	namespace App\Controllers\Compte;

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
			$userModel = new UserModel();
			$userId = session()->get('id_user');
	
			$rules = [
				'nom_user'    => 'required|max_length[50]',
				'prenom_user' => 'required|max_length[50]',
				'email_user'  => 'required|valid_email'
			];
	
			if ($this->validate($rules))
			{
				$userModel->update($userId, [
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
	
			$userModel = new UserModel();
			$userId = session()->get('id_user');
	
			$userModel->delete($userId);
			session()->destroy();
	
			session()->setFlashdata('success', 'Compte supprimé avec succès.');
			return redirect()->to('/');
		}

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