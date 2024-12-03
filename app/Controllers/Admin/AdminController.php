<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminController extends BaseController
{
	public function index()
	{

		$data = [
			'pageTitle' => 'Mon Compte'
		];

		echo view('commun/header', $data);
		echo view('Admin/index');
		echo view('commun/footer');
	}
}