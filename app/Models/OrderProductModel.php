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


		public function getProductsByOrder($idOrder)
		{
			return $this->select('product.p_name, product.p_price, image.img_path, order_product.quantity')
				->join('product', 'product.id_prod = order_product.id_prod')
				->join('product_image', 'product.id_prod = product_image.id_prod', 'left')
				->join('image', 'product_image.id_img = image.id_img', 'left')
				->where('order_product.id_order', $idOrder)
				->findAll();
		}
	}
?>