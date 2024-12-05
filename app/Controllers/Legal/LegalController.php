<?php

namespace App\Controllers\Legal;

use App\Controllers\BaseController;

class LegalController extends BaseController
{
    public function conditionsGenerales()
    {
        return view('Legal/conditions_generales');
    }

    public function politiqueConfidentialite()
    {
        return view('Legal/politique_confidentialite');
    }
}
