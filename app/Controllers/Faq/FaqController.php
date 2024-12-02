<?php

	namespace App\Controllers\Faq;

	use App\Controllers\BaseController;

	class FaqController extends BaseController
	{
		public function index()
		{
			$data = [
				'pageTitle' => 'FAQ'
			];

			echo view('commun/header', $data);
			echo view('Faq/faq');
			echo view('commun/footer');
		}
	}
?>