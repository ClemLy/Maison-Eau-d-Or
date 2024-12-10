<?php

	namespace App\Controllers\Carrousel;

	use App\Controllers\BaseController;
	use App\Models\CategoryModel;
	use App\Models\ProductModel;
	use App\Models\MediaModel; // Pour gérer les images du carrousel principal

	class CarrouselController extends BaseController
	{
		/**
		 * Affiche la vue de gestion des carrousels
		 */
		public function index()
		{
			$categoryModel = new CategoryModel();
			$mediaModel = new MediaModel();

			// Récupérer les catégories pour le carrousel des catégories
			$categories = $categoryModel->findAll();

			// Récupérer les images disponibles pour le carrousel principal
			$images = $mediaModel->findAll();

			$data = [
				'pageTitle' => 'Gestion Carrousel',
				'categories' => $categories,
				'images' => $images,
			];

			return view('Admin/manage_carrousel', $data);
		}

		/**
		 * Met à jour les catégories sélectionnées pour le carrousel des catégories
		 */
		public function updateCategoryCarousel()
		{
			$categoryModel = new CategoryModel();
			$selectedCategories = $this->request->getPost('selectedCategories'); // Tableau contenant les ID des catégories sélectionnées

			// Réinitialiser toutes les catégories
			$categoryModel->update(null, ['is_selected' => false]);

			// Mettre à jour les catégories sélectionnées
			if (!empty($selectedCategories))
			{
				foreach ($selectedCategories as $categoryId)
				{
					$categoryModel->update($categoryId, ['is_selected' => true]);
				}
			}

			return redirect()->to('/admin/carrousel')->with('success', 'Le carrousel des catégories a été mis à jour avec succès.');
		}

		/**
		 * Met à jour les images du carrousel principal
		 */
		public function updateMainCarousel()
		{
			$mediaModel = new MediaModel();
			$selectedImages = $this->request->getPost('selectedImages'); // Tableau contenant les ID des images sélectionnées

			// Réinitialiser toutes les images
			$mediaModel->update(null, ['is_selected' => false]);

			// Mettre à jour les images sélectionnées
			if (!empty($selectedImages))
			{
				foreach ($selectedImages as $imageId)
				{
					$mediaModel->update($imageId, ['is_selected' => true]);
				}
			}

			return redirect()->to('/admin/carrousel')->with('success', 'Le carrousel principal a été mis à jour avec succès.');
		}
	}
?>