<?php
/**
 * DatabaseManager component configuration file. Attention, configs might include runtime code
 * which depended on environment values only.
 *
 * @see \Spiral\Database\Config\DatabaseConfig
 */

use Spiral\Database\Driver\SQLite\SQLiteDriver;

return [
    'default'     => 'runtime',
    'aliases'     => ['default' => 'runtime'],
    'databases'   => [
        'runtime' => [
            'connection'  => 'runtime',
            'tablePrefix' => '',
        ],
    ],

    /*
     * Connection provides you lower access level to your database and database schema. You can link
     * as many connections to one database as you want.
     */
    'connections' => [
        'runtime' => [
            'driver'     => SQLiteDriver::class,
            'connection' => 'sqlite:' . directory('runtime') . 'runtime.db',
            'profiling'  => env('DEBUG', false),
            'username'   => 'sqlite',
            'password'   => '',
            'options'    => []
        ],
        /*{{connections}}*/
    ]
];
