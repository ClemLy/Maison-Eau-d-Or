<?php

	namespace App\Controllers\Blog;

	use App\Controllers\BaseController;
	use App\Controllers\Media\MediaController;

	use App\Models\BlogModel;
	use App\Models\MediaModel;

	class BlogController extends BaseController
	{
		public function index()
		{
			// Charger le modèle
			$blogModel = new BlogModel();

			// Récupérer le contenu avec id = 1
			$contentData = $blogModel->find(1);

			// Charger la vue spécifique Blog/blog.php
			$blogView = view('Blog/blog', [
				'content' => $contentData['content'] ?? 'Le contenu est vide.', // Passer le contenu spécifique
			]);

			// Passer les données à la vue principale
			$data = [
				'pageTitle' => 'Blog',
				'content'   => $blogView, // Inclure la vue comme contenu principal
			];

			// Charger la vue principale
			return view('Layout/main', $data);
		}

		public function ajouterArticlePost()
		{
			if ($this->request->getMethod() === 'post')
			{
				$mediaController = new MediaController();
				$blogModel       = new BlogModel();
				$data    = $this->request->getPost();
				$file    = $this->request->getFile('new_img');
				$imageId = null;

				try
				{
					// Gestion de l'image
					if (!empty($data['existing_img']))
					{
						$imageId = $data['existing_img'];
					}
					elseif ($file && $file->isValid())
					{
						$imageId = $mediaController->uploadImage($file);
						if (!$imageId)
						{
							throw new \RuntimeException("L'upload de l'image a échoué.");
						}
					}

					if (!$imageId)
					{
						throw new \RuntimeException("Aucune image sélectionnée ou uploadée.");
					}

					// Insertion de l'article
					$blogModel->save([
						'id_img'    => $imageId,
						'art_title' => $data['title'],
						'art_text'  => $data['content']
					]);

					

					//return redirect()->to('/admin/blog/ajouter')->with('success', 'Article ajouté avec succès.');
				}
				catch (\Exception $e)
				{
					return redirect()->back()->with('error', $e->getMessage());
				}
			}
		}

		public function ajouterArticleGet()
		{
			$mediaModel = new MediaModel();

			$data = [
				'pageTitle' => 'Blog',
				'content'   => view('Admin/add_article'),
				'images'    => $mediaModel->findAll()
			];
	
			return View('Layout/main', $data);
		}
	}
?>