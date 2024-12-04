<?php

	namespace App\Controllers\Faq;

	use App\Controllers\BaseController;
	use App\Models\FaqModel;

	class FaqController extends BaseController
	{
		public function index()
		{
			// Charger le modèle
			$faqModel = new FaqModel();

			// Récupérer le contenu avec id = 1
			$contentData = $faqModel->find(1);

			$faqView = view('Faq/faq', [
				'content' => $contentData['content'] ?? 'Le contenu est vide.', // Passer le contenu spécifique
			]);

			// Passer les données à la vue principale
			$data = [
				'pageTitle' => 'F.A.Q',
				'content'   => $faqView, // Inclure la vue comme contenu principal
			];

			// Charger la vue principale
			return view('Layout/main', $data);
		}
	}
?>