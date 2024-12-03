<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Vérifiez si l'utilisateur est connecté & admin
        if (!session()->get('isLoggedIn') || !session()->get('admin') ) {
            // Redirigez vers la page de connexion
            return redirect()->to('/signin');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Code après la requête (optionnel)
    }
}