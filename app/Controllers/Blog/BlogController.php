<?php

	namespace App\Controllers\Blog;

	use App\Controllers\BaseController;
	use App\Controllers\Media\MediaController;

	use App\Models\BlogModel;
	use App\Models\MediaModel;
	use App\Models\AccountModel;

	class BlogController extends BaseController
	{

		private BlogModel $blogModel;
		private MediaModel $mediaModel;

		public function __construct()
		{
			$this->blogModel = new BlogModel();
			$this->mediaModel = new MediaModel();
		}

		public function index()
		{
			// Récupérer le contenu avec id = 1
			$contentData = $this->blogModel->getArticle();
			$images = $this->mediaModel->findAll();

			// Charger la vue spécifique Blog/blog.php
			$blogView = view('Blog/blog', [
				
				'articles' => $contentData ?? 'Le contenu est vide.', // Passer le contenu spécifique
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

			if ($this->request->getMethod() === 'POST')
			{
				$mediaController = new MediaController();
				$blogModel       = new BlogModel();
				$accountModel    = new AccountModel();
				$data    = $this->request->getPost();
		
				try
				{

					// Insertion de l'article
					$blogModel->save([
						'id_img'    => $data['existing_imgs'],
						'art_title' => $data['title'],
						'art_text'  => $data['content']
					]);

			

					$users = $accountModel->where('newsletter', true)->findAll();

					$email = \Config\Services::email();
					foreach ($users as $user)
					{
						$email->setFrom(env('email_user', ''), 'Maison Eau D\'Or');
						$email->setTo($user['email']);
						$email->setSubject('Nouveau blog sur notre site');
						$email->setMessage("Bonjour, un nouvel article a été publié sur notre blog : <br><br>
							<strong>" . esc($data['title']) . "</strong><br>
							<a href='" . site_url('blog/' . $blogModel->insertID()) . "'>Cliquez ici pour lire l'article complet.</a>");

						// Envoi de l'e-mail
						$email->send();
					}	
					return redirect()->to('/admin/blog')->with('success', 'Article ajouté avec succès.');
				}
				catch (\Exception $e)
				{
					return redirect()->back()->with('error', $e->getMessage());
				}
			}
		}

		public function ajouterArticleGet()
		{
			$var = 
			[
				'images'    => $this->mediaModel->findAll(),
			];

			$data = [
				
				'pageTitle' => 'Blog',
				'content'   => view('Admin/add_article',$var),
				
			];
	
			return View('Layout/main', $data);
		}

		public function lireArticle($id_art)
		{
			$contentData = $this->blogModel->getArticleById($id_art);

			$blogView = view('Blog/read_blog', [
				'article' => $contentData ?? 'L\'article est introuvable.',
			]);

			$data = [
				'pageTitle' => 'Blog',
				'content'   => $blogView,
			];

			return view('Layout/main', $data);
		}


		public function modifierArticleGet($id_art)
		{
			$blogModel = new BlogModel();
			$mediaModel = new MediaModel();

			// Vérifier si l'article existe
			$article = $blogModel->getArticleById($id_art);


			if (!$article)
			{
				return redirect()->to('/admin/blog')->with('error', 'Article introuvable.');
			}

			$article = $article[0];


			// Préparer les données pour la vue
			$data = [
				'pageTitle'      => 'Modifier Article',
				'currentContent' => $article['art_text'], // Le contenu existant de l'article
				'article'        => $article,
				'content'        => view('Admin/edit_article', [
					'currentContent' => $article['art_text'], // Injecter dans Quill
					'article'        => $article,
					'images' => $mediaModel->findAll()
				]),
			];

			return view('Layout/main', $data);
		}

		public function modifierArticlePost()
		{
			helper(['form']);
			$blogModel = new BlogModel();

			if ($this->request->getMethod() === 'POST')
			{
				
				// Mise à jour de l'article
				$updatedData = [
					'art_title' => $this->request->getPost('art_title'),
					'art_text'  => $this->request->getPost('content'),
					'id_img' => $this->request->getPost('existing_imgs')
				];

				$articleId = $this->request->getPost('id_art');

				$blogModel->update($articleId,$updatedData);

				return redirect()->to('/admin/blog')->with('success', 'Article modifié avec succès.');
			}

		}


		public function supprimerArticle($id_art)
		{
			$blogModel = new \App\Models\BlogModel();

			// Vérifie si l'article existe
			$article = $blogModel->find($id_art);
			if (!$article)
			{
				return redirect()->to('/admin/blog')->with('error', 'L\'article n\'existe pas.');
			}

			// Supprime l'article
			if ($blogModel->delete($id_art))
			{
				return redirect()->to('/admin/blog')->with('success', 'L\'article a été supprimé avec succès.');
			}
			else
			{
				return redirect()->to('/admin/blog')->with('error', 'Une erreur est survenue lors de la suppression.');
			}
		}

	}
?>