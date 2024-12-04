<?php

	namespace App\Controllers\APropos;

	use App\Controllers\BaseController;
	use App\Models\AProposModel;

	class AProposController extends BaseController
	{
		public function index()
		{
			// Charger le modèle
			$aProposModel = new AProposModel();

			// Récupérer le contenu avec id = 1
			$contentData = $aProposModel->find(1);

			// Charger la vue spécifique APropos/apropos.php
			$aproposView = view('APropos/apropos', [
				'content' => $contentData['content'] ?? 'Le contenu est vide.', // Passer le contenu spécifique
			]);

			// Passer les données à la vue principale
			$data = [
				'pageTitle' => 'À propos',
				'content'   => $aproposView, // Inclure la vue comme contenu principal
			];

			// Charger la vue principale
			return view('Layout/main', $data);
		}
	}
?>