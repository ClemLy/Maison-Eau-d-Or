<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    /**
     * The directory that holds the Migrations and Seeds directories.
     */
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    /**
     * Lets you choose which connection group to use if no other is specified.
     */
    public string $defaultGroup = 'default';

    /**
     * The default database connection.
     *
     * @var array<string, mixed>
     */
    public array $default;

    public array $tests = [
        'DSN'         => '',
        'hostname'    => '127.0.0.1',
        'username'    => '',
        'password'    => '',
        'database'    => ':memory:',
        'DBDriver'    => 'SQLite3',
        'DBPrefix'    => 'db_', // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS
        'pConnect'    => false,
        'DBDebug'     => true,
        'charset'     => 'utf8',
        'DBCollat'    => '',
        'swapPre'     => '',
        'encrypt'     => false,
        'compress'    => false,
        'strictOn'    => false,
        'failover'    => [],
        'port'        => 3306,
        'foreignKeys' => true,
        'busyTimeout' => 1000,
        'dateFormat'  => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    public function __construct()
    {
        parent::__construct();

        // Configuration de la base de données par défaut
        $this->default = [
            'hostname'   => env('db_hostname', 'localhost'),
            'username'   => env('db_username', 'root'),
            'password'   => env('db_password', ''),
            'database'   => env('db_name', 'test'),
            'DBDriver'   => env('db_DBDriver', 'MySQLi'),
            'DBPrefix'   => '',
            'pConnect'   => false,
            'DBDebug'    => (ENVIRONMENT !== 'production'),
            'charset'    => 'utf8',
            'DBCollat'   => 'utf8_general_ci',
            'swapPre'    => '',
            'encrypt'    => false,
            'compress'   => false,
            'strictOn'   => false,
            'failover'   => [],
            'port'       => env('db_port', ''),
            'numberNative' => false,
            'dateFormat' => [
                'date'     => 'Y-m-d',
                'datetime' => 'Y-m-d H:i:s',
                'time'     => 'H:i:s',
            ],
        ];

        // Si on est en mode "testing", utiliser la configuration "tests".
        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }
    }
}