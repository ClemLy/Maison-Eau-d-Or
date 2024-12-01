<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Pager extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Templates
     * --------------------------------------------------------------------------
     *
     * Pagination links are rendered out using views to configure their
     * appearance. This array contains aliases and the view names to
     * use when rendering the links.
     *
     * Within each view, the Pager object will be available as $pager,
     * and the desired group as $pagerGroup;
     *
     * @var array<string, string>
     */
    public array $templates = [
        'default'        => 'CodeIgniter\Pager\Views\default_simple',
        'default_full'   => 'CodeIgniter\Pager\Views\default_full',
        'default_simple' => 'CodeIgniter\Pager\Views\default_simple',
        'default_head'   => 'CodeIgniter\Pager\Views\default_head',
        'modif'          => 'App\Views\pagination\modif_full',

        'full_modif'     => 'App\Views\pagination\full_modif', // Template pour commentaires
        'custom'         => 'App\Views\pagination\perso',      // Template pour articles
    ];

    /**
     * --------------------------------------------------------------------------
     * Items Per Page
     * --------------------------------------------------------------------------
     *
     * The default number of results shown in a single page.
     */
    public int $perPage = 20;


    /* ARTICLES PERSO */

    // Configuration de pagination pour les articles
    public array $article = [
        'perPage'  => 5,
        'template' => 'custom',    // Utilisation du template 'custom' pour les articles
        'group'    => 'art',       // Groupe spécifique pour les articles
    ];

    // Configuration de pagination pour les commentaires
    public array $commentaire = [
        'perPage'  => 1,
        'template' => 'full_modif',     // Utilisation du template 'full_modif' pour les commentaires
        'group'    => 'commentaire', // Groupe spécifique pour les commentaires
    ];
}