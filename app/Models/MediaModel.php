<?php

namespace App\Models;

use CodeIgniter\Model;

class MediaModel extends Model
{
    protected $table = 'image'; // Nom de la table
    protected $primaryKey = 'id_img'; // Clé primaire
    protected $allowedFields = ['img_name', 'img_path']; // Colonnes modifiables

    // Méthode pour trouver une image par son nom
    public function getImagesByProductId($id_prod)
    {

        // Requête pour récupérer les images associées au produit
        return $this->select('image.id_img, image.img_path, image.img_name')
            ->join('product_image', 'product_image.id_img = image.id_img')
            ->where('product_image.id_prod', $id_prod)
            ->findAll();
    }

    public function hasRelations($id_img)
    {
        // Vérifier les relations avec les produits
        $relatedProducts = $this->db->table('product_image')
            ->where('id_img', $id_img)
            ->countAllResults();

        // Vérifier les relations avec les articles
        $relatedArticles = $this->db->table('article')
            ->where('id_img', $id_img)
            ->countAllResults();

        // Vérifier les relations avec le carrousel principal
        $relatedCarousels = $this->db->table('carousel')
            ->where('id_img', $id_img)
            ->countAllResults();

        return ($relatedProducts > 0 || $relatedArticles > 0 || $relatedCarousels > 0);
    }

    // Méthode pour récupérer les informations complètes des images
    public function getImageInformation()
    {
        // Récupérer toutes les images depuis la base de données
        $images = $this->findAll();

        $imageDetails = [];

        foreach ($images as $image) {
            $imgPath = $image['img_path'];

            // Relations avec d'autres entités (produits, articles, etc.)
            $relatedProducts = $this->db->table('product_image')
                ->select('product.id_prod, product.p_name')
                ->join('product', 'product.id_prod = product_image.id_prod')
                ->where('product_image.id_img', $image['id_img'])
                ->get()
                ->getResultArray();

            $relatedArticles = $this->db->table('article')
                ->select('article.id_art, article.art_title')
                ->where('article.id_img', $image['id_img'])
                ->get()
                ->getResultArray();

            $relatedCarousels = $this->db->table('carousel')
                ->select('carousel.id_car, carousel.link_car')
                ->where('carousel.id_img', $image['id_img'])
                ->get()
                ->getResultArray();

            if (file_exists(FCPATH .$imgPath))
            {

                // Récupérer la taille et la dernière date de modification
                $fileSize = filesize( FCPATH .$imgPath); // Taille en octets
                $lastModified = date("Y-m-d H:i:s", filemtime(FCPATH .$imgPath)); // Dernière modification

                // Ajouter les informations de l'image
                $imageDetails[] = [
                    'id_img'            => $image['id_img'],
                    'img_name'          => $image['img_name'],
                    'img_path'          => $imgPath,
                    'file_size'         => $fileSize, // Taille en octets
                    'last_modified'     => $lastModified, // Dernière modification
                    'related_products'  => $relatedProducts, // Produits liés
                    'related_articles'  => $relatedArticles, // Articles liés
                    'related_carousels' => $relatedCarousels, // Carrousels liés
                ];
            } else {
                // Si le fichier n'existe pas, enregistrer un message d'erreur
                $imageDetails[] = [
                    'id_img'            => $image['id_img'],
                    'img_name'          => $image['img_name'],
                    'img_path'          => $imgPath,
                    'related_products'  => $relatedProducts, // Produits liés
                    'related_articles'  => $relatedArticles, // Articles liés
                    'related_carousels' => $relatedCarousels, // Carrousels liés
                ];
            }
        }

        return $imageDetails;
    }
}