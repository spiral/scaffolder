<?php
/**
 * Migrations component configuration file. Attention, configs might include runtime code which
 * depended on environment values only.
 *
 * @see \Spiral\Migrations\Config\MigrationConfig
 */
return [
    'directory' => directory('app') . 'migrations/',
    'database'  => 'runtime',
    'table'     => 'migrations',
    'safe'      => env('SPIRAL_ENV') === 'develop'
];