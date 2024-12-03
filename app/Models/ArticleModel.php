<?php
	namespace App\Models;
	use CodeIgniter\Model;

	class ArticleModel extends Model
	{
		protected $table         = 'article';
		protected $primaryKey    = 'id_art';
		protected $allowedFields = [
			'id_img',
            'art_title',
            'art_text',
            'art_date'
		];
	}
?>