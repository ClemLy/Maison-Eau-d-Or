<?php

	namespace App\Models;

	use CodeIgniter\Model;

	class BlogModel extends Model
	{
		protected $table         = 'article';
		protected $primaryKey    = 'id_art';
		protected $allowedFields = ['id_img', 'art_title', 'art_text', 'art_date'];
	
	

		public function getArticle()
		{
			// Récupérer toute les images associées à un article
			return $this->select('image.id_img, image.img_path, image.img_name,article.*')
				->join('image', 'article.id_img = image.id_img')
				->findAll();
		}

		public function getArticleById($id_art)
		{
			// Récupérer toute les images associées à un article
			return $this->select('image.id_img, image.img_path, image.img_name,article.*')
				->join('image', 'article.id_img = image.id_img')
				->where('article.id_art', $id_art)
				->findAll();
		}
	}
?>