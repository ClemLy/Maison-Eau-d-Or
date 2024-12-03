<?php
	namespace App\Models;
	use CodeIgniter\Model;

	class ImageModel extends Model
	{
		protected $table         = 'image';
		protected $primaryKey    = 'id_img';
		protected $allowedFields = [
			'img_name',
            'img_path'
		];
	}
?>