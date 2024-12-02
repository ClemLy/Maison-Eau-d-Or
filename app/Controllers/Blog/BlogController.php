<?php

	namespace App\Controllers\Blog;

	use App\Controllers\BaseController;

	class BlogController extends BaseController
	{
		public function index()
		{
			$data = [
				'pageTitle' => 'Blog'
			];

			echo view('commun/header', $data);
			echo view('Blog/blog');
			echo view('commun/footer');
		}
	}
?>