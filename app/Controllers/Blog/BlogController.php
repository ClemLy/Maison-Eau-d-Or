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
				$data            = $this->request->getPost();
		
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
						$email->setMessage($this->getEmail($user, $data, $blogModel));
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

		public function getEmail($user, $data, $blogModel)
		{
			return "<!DOCTYPE html>
			<html lang=\"fr\">
			<body style=\"margin: 0; padding: 0; background-color: #FFFFFF; font-family: Arial, sans-serif;\">
			  <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"background-color: #FFFFFF; margin: 0; padding: 0;\">
				<tr>
				  <td align=\"center\">
					<!-- Container -->
					<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border: 1px solid #ccc; background-color: #fff; padding: 20px;\">
					  <!-- Header -->
					  <tr>
						<td align=\"center\" style=\"padding: 10px 0;\">
						  <a href=\"".site_url('/')."\">
							<img src=\"https://i.imgur.com/2Objlik.png\" alt=\"Logo\" width=\"200\" style=\"display: block;\">
						  </a>
						</td>
					  </tr>
					  <!-- Navigation Menu -->
					  <tr>
						<td align=\"center\" style=\"padding: 20px 0;\">
						  <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"60%\">
							<tr>
							  <td align=\"center\" style=\"padding: 5px;\">
								<a href=\"".site_url('/')."\" style=\"display: inline-block; text-decoration: none; color: #d4af37; font-size: 14px; font-weight: bold; border: 2px solid #d4af37; padding: 10px 20px; border-radius: 20px;\">Accueil</a>
							  </td>
							  <td align=\"center\" style=\"padding: 5px;\">
								<a href=\"".site_url('/boutique')."\" style=\"display: inline-block; text-decoration: none; color: #d4af37; font-size: 14px; font-weight: bold; border: 2px solid #d4af37; padding: 10px 20px; border-radius: 20px;\">Boutique</a>
							  </td>
							  <td align=\"center\" style=\"padding: 5px;\">
								<a href=\"". site_url('/blog') ."\" style=\"display: inline-block; text-decoration: none; color: #d4af37; font-size: 14px; font-weight: bold; border: 2px solid #d4af37; padding: 10px 20px; border-radius: 20px;\">Blog</a>
							  </td>
							</tr>
						  </table>
						</td>
					  </tr>
					  <!-- Content -->
					  <tr>
						<td align=\"center\" style=\" padding: 20px;\">
							<h2 style=\"color: #000; font-size: 24px; margin: 0; text-align: left; \"> Un nouvel article a été publié !</h2>
							<p style=\"color: #333; font-size: 14px; line-height: 1.5; text-align: left; \"> Cher ".$user['first_name'].",</p>
							<p style=\"color: #333; font-size: 14px; line-height: 1.5; text-align: left; \"> Un nouvel article a été publié sur notre blog : <b>".$data['title']."</b>  </p>
							<a href=\"".site_url('blog/' . $blogModel->insertID())."\" style=\"text-align: center; display: inline-block; background-color: #d4af37; color: #fff; text-decoration: none; padding: 10px 20px; font-size: 16px; border-radius: 20px;margin-top: 30px;\"> Accéder à l'article </a>
							<p style=\"color: #333; font-size: 11px; line-height: 1.5; text-align: left; margin-top: 50px;\"> Pour vous désinscrire des newletters et ne plus recevoir de mail publicitaire, connecter vous <a href=\"".site_url('/account/update')."\"> ici puis décocher l'inscription aux newletters. </a> </p>
						</td>
					  </tr>
					  <!-- Footer -->
					  <tr>
						<td align=\"center\" style=\"padding: 20px; background-color: #f7f7f7; margin-top: 50px;\">
						  <p style=\"color: #333; font-size: 14px; margin: 0;\">Retrouvez-nous sur nos réseaux</p>
						  <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top: 10px;\">
							<tr>
							  <td style=\"padding: 0 5px;\">
								<a href=\"https://www.facebook.com/maisoneaudor76/\">
								  <img src=\"https://eryasvi.stripocdn.email/content/assets/img/social-icons/logo-black/facebook-logo-black.png\" alt=\"Facebook\" width=\"32\" style=\"display: block;\">
								</a>
							  </td>
							  <td style=\"padding: 0 5px;\">
								<a href=\"https://www.instagram.com/maisoneaudor/\">
								  <img src=\"https://eryasvi.stripocdn.email/content/assets/img/social-icons/logo-black/instagram-logo-black.png\" alt=\"Instagram\" width=\"32\" style=\"display: block;\">
								</a>
							  </td>
							</tr>
						  </table>
						</td>
					  </tr>
					</table>
				  </td>
				</tr>
			  </table>
			</body>
			</html>";
		}
	}
?>