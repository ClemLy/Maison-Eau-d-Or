<?php
	namespace App\Controllers\Compte;

	use App\Models\AccountModel;
	use App\Controllers\BaseController;

	class SigninController extends BaseController
	{
		public function __construct()
		{
			helper(['form', 'cookie']);
		}
		
		public function index()
		{
			if (session()->get('isLoggedIn'))
			{
				if (session()->get('auto_redirect'))
				{
					return redirect()->to('/account');
				}
			}

			$data = [
				'pageTitle' => 'Connexion'
			];
		
			echo view('commun/header', $data);
			echo view('Compte/signin');
			echo view('commun/footer');
		}

		public function loginAuth()
		{
			$session      = session();
			$accountModel = new AccountModel();
			$email        = $this->request->getVar('email');
			$password     = $this->request->getVar('password');
			$remember     = $this->request->getVar('remember');

			$data = $accountModel->where('email', $email)->first();

			if ($data)
			{
				$pass = $data['password'];
				$authenticatePassword = password_verify($password, $pass);

				if ($authenticatePassword)
				{
					$ses_data = [
						'id_user'      => $data['id_user'],
						'first_name'   => $data['first_name'],
						'last_name'    => $data['last_name'],
						'email'        => $data['email'],
						'phone_number' => $data['phone_number'] ?? null,
						'isLoggedIn'   => true
					];

					$session->set($ses_data);

					if ($data['is_verified'] == 'f')
					{
						$session->setFlashdata('error', 'Votre compte n\'est pas encore activé. Veuillez vérifier votre boîte mail.');
						return redirect()->to('/signin');
					}

					// Gestion du "Se souvenir de moi"
					if ($remember)
					{
						$rememberToken = bin2hex(random_bytes(16)); // Génère un token unique
						$accountModel->update($data['id_user'], ['remember_token' => $rememberToken]);
						set_cookie('remember_cookie', $rememberToken, 84600);  // Cookie valide pour 24 heures
					}

					echo "<script>window.location.href='/account';</script>";
				}
				else
				{
					$session->setFlashdata('error', 'Mot de passe incorrect.');
					return redirect()->to('/signin');
				}
			}
			else
			{
				$session->setFlashdata('error', 'Cet email n\'existe pas.');
				return redirect()->to('/signin');
			}
		}
	}
?>