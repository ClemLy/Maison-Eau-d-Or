<?php

	namespace App\Controllers\APropos;

	use App\Controllers\BaseController;

	class AProposController extends BaseController
	{
		public function index()
		{
			$data = [
				'pageTitle' => 'À propos'
			];

			echo view('commun/header', $data);
			echo view('APropos/apropos');
			echo view('commun/footer');
		}
	}
?>