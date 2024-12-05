<?php

namespace App\Controllers\Legal;

use App\Controllers\BaseController;

class LegalController extends BaseController
{
    public function conditionsGenerales()
    {
        $data = [
            'pageTitle' => 'Conditions Générales',
            'content'   => view('Legal/conditions_generales') // Contenu principal
        ];

        return View('Layout/main', $data);
    }

    public function politiqueConfidentialite()
    {
        $data = [
            'pageTitle' => 'Politique de Confidentialité',
            'content'   => view('Legal/politique_confidentialite') // Contenu principal
        ];

        return View('Layout/main', $data);
    }
}
