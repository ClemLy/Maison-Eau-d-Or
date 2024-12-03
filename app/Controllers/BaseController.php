<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\AccountModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

		// Automatic login if a valid "remember_token" cookie exists
		$this->autoLogin();
    }

    /**
	 * Checks for a valid "remember_token" cookie and logs in the user automatically.
	 *
	 * @return void
	 */
	private function autoLogin(): void
	{
		helper('cookie');
		
		$request = service('request');
		$rememberToken = $this->request->getCookie('remember_cookie');

		if ($rememberToken)
		{
			$accountModel = new AccountModel();
			$account = $accountModel->where('remember_token', $rememberToken)->first();

			if ($account)
			{
				// Crée la session pour l'utilisateur
				session()->set([
					'id_user'     => $account['id_user'],
					'first_name'  => $account['first_name'],
					'last_name'   => $account['last_name'],
					'email'       => $account['email'],
					'isLoggedIn'  => true
				]);

				// Ajoute un flag de redirection dans la session
				session()->set('auto_redirect', true);
			}
		}
		else
		{
			session()->set('auto_redirect', false);
			delete_cookie('remember_cookie');
		}
	}
}
