<?php

	namespace App\Controllers\Panier;

	use App\Controllers\BaseController;

	class PanierController extends BaseController
	{
		public function index()
		{
			$data = [
				'pageTitle' => 'Panier'
			];

			echo view('commun/header', $data);
			echo view('Panier/panier');
			echo view('commun/footer');
		}
	}
?>