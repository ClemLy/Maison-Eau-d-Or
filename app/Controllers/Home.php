<?php

	namespace App\Controllers;

	use App\Controllers\BaseController;

	class Home extends BaseController
	{
		public function index()
		{
			$data = [
				'pageTitle' => 'Accueil'
			];

			echo view('commun/header', $data);
			echo view('Home/home');
			echo view('commun/footer');
		}
	}
?>