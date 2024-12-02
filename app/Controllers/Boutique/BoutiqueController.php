<?php

	namespace App\Controllers\Boutique;

	use App\Controllers\BaseController;

	class BoutiqueController extends BaseController
	{
		public function index()
		{
			$data = [
				'pageTitle' => 'Boutique'
			];

			echo view('commun/header', $data);
			echo view('Boutique/boutique');
			echo view('commun/footer');
		}
	}
?>