<?php
	namespace App\Models;
	use CodeIgniter\Model;

	class CarouselModel extends Model
	{
		protected $table         = 'carousel';
		protected $primaryKey    = 'id_car';
		protected $allowedFields = [
			'id_img',
            'link_car'
		];
	}
?>