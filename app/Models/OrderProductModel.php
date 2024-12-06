<?php
	namespace App\Models;
	use CodeIgniter\Model;

	class OrderProductModel extends Model
	{
		protected $table         = 'order_product';
		protected $allowedFields = [
			'id_order',
			'id_prod',
            'quantity'
		];
	}
?>