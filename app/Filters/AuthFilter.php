<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Vérifiez si l'utilisateur est connecté
        if (!session()->get('isLoggedIn')) {
            // Redirigez vers la page de connexion
            return redirect()->to('/signin');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Code après la requête (optionnel)
    }
}