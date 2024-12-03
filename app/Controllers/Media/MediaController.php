<?php

namespace App\Controllers\Media;

use App\Controllers\BaseController;
use App\Models\MediaModel;

class MediaController extends BaseController
{
    protected $imageModel;

    public function __construct()
    {
        $this->imageModel = new MediaModel();
    }

    // Afficher la médiathèque
    public function index()
    {
        $data = [
            'pageTitle' => 'Médiathèque',
            'images'    => $this->imageModel->findAll()
        ];

        return view('media_library', $data);
    }

    // Uploader une nouvelle image
    public function uploadImage($file)
    {
        if ($file && $file->isValid()) {
            // Générer un nom unique pour le fichier
            $newName = $file->getRandomName();

            // Déplacer le fichier vers public/uploads
            $filePath = FCPATH . 'uploads/';
            if (!is_dir($filePath)) {
                mkdir($filePath, 0777, true);
            }

            $file->move($filePath, $newName);

            // Sauvegarder dans la table IMAGE
            $imageModel = new \App\Models\MediaModel();
            return $imageModel->insert([
                'img_name' => $file->getClientName(), // Nom original
                'img_path' => '/uploads/' . $newName  // Chemin public
            ]);
        }

        return null;
    }
}