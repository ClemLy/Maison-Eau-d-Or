<?php

	namespace App\Controllers\Compte;

	use App\Controllers\BaseController;

	class CompteController extends BaseController
	{
		public function index()
		{
			$data = [
				'pageTitle' => 'Mon Compte'
			];

			echo view('commun/header', $data);
			echo view('Compte/compte');
			echo view('commun/footer');
		}

        public function connexion()
        {

        }

        public function inscription()
        {

        }

        public function deconnexion()
        {

        }

        public function modifier()
        {

        }

        public function supprimer()
        {

        }


	}
?>