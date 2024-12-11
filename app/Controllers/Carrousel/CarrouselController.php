<?php

	namespace App\Controllers\Carrousel;

	use App\Controllers\BaseController;
	use App\Models\CategoryModel;
	use App\Models\ProductModel;
	use App\Models\MediaModel; // Pour gérer les images du carrousel principal
	use App\Models\ShowcaseModel;
	use App\Models\CarouselModel;

	class CarrouselController extends BaseController
	{
		/**
		 * Affiche la vue de gestion des carrousels
		 */
		public function index()
		{
			$categoryModel = new CategoryModel();
			$mediaModel    = new MediaModel();
			$showcaseModel = new ShowcaseModel();
			$carouselModel = new CarouselModel();


			// Récupérer les images du carrousel principal
			$carrouselImages = $carouselModel
				->select('carousel.id_car, carousel.id_img, image.img_path')
				->join('image', 'carousel.id_img = image.id_img')
				->findAll();

			// Récupérer toutes les images disponibles pour le carrousel
			$images = $mediaModel->findAll();

			// Récupérer toutes les catégories disponibles
			$categories = $categoryModel->findAll();

			// Récupérer les catégories du carrousel actuel avec leur position
			$showcaseCategories = $showcaseModel
				->select('showcase.id_show, category.cat_name, category.id_cat')
				->join('category', 'showcase.id_cat = category.id_cat')
				->orderBy('id_show') // Respecter l'ordre défini par `id_show`
				->findAll();

			// Ajouter un indicateur `active` et `position` pour chaque catégorie
			foreach ($categories as &$category)
			{
				$category['active'] = false; // Par défaut, la catégorie n'est pas active
				$category['position'] = null; // Par défaut, aucune position

				foreach ($showcaseCategories as $index => $showcaseCategory)
				{
					if ($category['id_cat'] === $showcaseCategory['id_cat'])
					{
						$category['active'] = true;
						$category['position'] = $index + 1; // La position dans `showcase` (commence à 1)
						break; // Sortir dès que la catégorie est trouvée
					}
				}
			}

			// Trier les catégories par leur position
			usort($categories, function ($a, $b)
			{
				// Les catégories sans position vont en bas
				if ($a['position'] === null) return 1;
				if ($b['position'] === null) return -1;
				return $a['position'] - $b['position'];
			});

			// Passer les données à la vue
			$carrouselView = view('Admin/manage_carrousel', [
				'categories'         => $categories,
				'carrousel'          => $carrouselImages,
				'showcaseCategories' => $showcaseCategories,
				'images'             => $images
			]);

			$data = [
				'pageTitle' => 'Gestion Carrousel',
				'content'   => $carrouselView,
			];

			return view('Layout/main', $data);
		}


		/**
		 * Met à jour les catégories sélectionnées pour le carrousel des catégories
		 */
		public function updateCategoryCarousel()
		{
			$showcaseModel = new ShowcaseModel();
			

			// Vérifiez que la requête est bien une requête AJAX
			if (!$this->request->isAJAX())
			{  		
				return $this->response->setJSON([
					'success' => false,
					'message' => 'Requête non valide.'
				]);
			}
			
			// Récupérez les données JSON brutes envoyées par le client
			$rawData = $this->request->getBody();

			// Analysez les données JSON
			$decodedData = json_decode($rawData, true);

			// Vérifiez que le JSON est valide
			if (json_last_error() !== JSON_ERROR_NONE)
			{
				return $this->response->setJSON([
					'success' => false,
					'message' => 'JSON invalide : ' . json_last_error_msg()
				]);
			}

			// Vérifiez si les données nécessaires sont présentes
			if (empty($decodedData['categories']) || !is_array($decodedData['categories']))
			{
				return $this->response->setJSON([
					'success' => false,
					'message' => 'Les données des catégories sont absentes ou mal formatées.'
				]);
			}
						
			$showcaseModel->truncate(); // Vider la table

        	// Préparer les données pour l'insertion
			$categories = [];
			foreach ($decodedData['categories'] as $index => $category)
			{
				// Vérifier si la catégorie est activée
				if (!isset($category['id'], $category['active']) || !$category['active'])
				{
					continue; // Ignorer les catégories désactivées
				}

				$categories[] = [
					'id_cat'  => $category['id'], // ID de la catégorie
				];
			}

			// Insérer les catégories activées en lot
			if (!empty($categories))
			{
				$showcaseModel->insertCategoriesBatch($categories);
			}
			
			

			// Retournez une réponse JSON de succès
			return $this->response->setJSON([
				'success' => true,
				'message' => 'Mise à jour effectuée.',
			]);
		}

		/**
		 * Met à jour les images du carrousel principal
		 */
		public function updateMainCarousel()
		{
			// Vérifiez que la requête est bien une requête AJAX
			if (!$this->request->isAJAX())
			{
				return $this->response->setJSON([
					'success' => false,
					'message' => 'Requête non valide.'
				]);
			}

			// Récupérez les données JSON envoyées
			$rawData = $this->request->getBody();
			$decodedData = json_decode($rawData, true);

			// Vérifiez que le JSON est valide
			if (json_last_error() !== JSON_ERROR_NONE)
			{
				return $this->response->setJSON([
					'success' => false,
					'message' => 'JSON invalide : ' . json_last_error_msg()
				]);
			}

			// Vérifiez que les données nécessaires sont présentes
			if (empty($decodedData['images']) || !is_array($decodedData['images']))
			{
				return $this->response->setJSON([
					'success' => false,
					'message' => 'Les données des images sont absentes ou mal formatées.'
				]);
			}

			$carouselModel = new CarouselModel();

			// Vider le carrousel actuel
			$carouselModel->truncate();

			// Préparer les nouvelles entrées
			$newCarouselEntries = [];
			foreach ($decodedData['images'] as $image)
			{
				if (!empty($image['id']))
				{
					$newCarouselEntries[] = [
						'id_img'   => $image['id'],
						'link_car' => '' // Ajouter ici un lien personnalisé si nécessaire
					];
				}
			}

			// Insérer les nouvelles images si elles existent
			if (!empty($newCarouselEntries))
			{
				$carouselModel->insertBatch($newCarouselEntries);
			}

			// Retournez une réponse JSON de succès
			return $this->response->setJSON([
				'success' => true,
				'message' => 'Le carrousel principal a été mis à jour avec succès.'
			]);
		}

	}
?>