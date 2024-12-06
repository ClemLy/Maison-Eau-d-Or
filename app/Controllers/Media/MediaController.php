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
    public function uploadImage()
    {
        $file = $this->request->getFile('new_img');
        if ($file && $file->isValid()) {
            try {
                $newName = $file->getRandomName();
                $filePath = FCPATH . 'uploads/';

                if (!is_dir($filePath)) {
                    mkdir($filePath, 0777, true);
                }

                $file->move($filePath, $newName);

                $imageModel = new \App\Models\MediaModel();
                $imageId = $imageModel->insert([
                    'img_name' => $file->getClientName(),
                    'img_path' => '/uploads/' . $newName
                ]);

                if ($imageId) {
                    return $this->response->setJSON([
                        'success' => true,
                        'image' => [
                            'id_img' => $imageId,
                            'img_name' => $file->getClientName(),
                            'img_path' => '/uploads/' . $newName,
                        ]
                    ]);
                }

                throw new \RuntimeException('Erreur lors de la sauvegarde en base de données.');
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Fichier invalide ou non envoyé.'
        ]);
    }
}