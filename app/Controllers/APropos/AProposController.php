<?php

	namespace App\Controllers\APropos;

	use App\Controllers\BaseController;

	class AProposController extends BaseController
	{
		public function index()
		{
			$data = [
				'pageTitle' => 'À propos',
				'content'   => view('APropos/apropos') // Contenu principal
			];
	
			return View('Layout/main', $data);
		}
	}
?>