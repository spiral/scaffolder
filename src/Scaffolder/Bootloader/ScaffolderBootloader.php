<?php
/**
 * Spiral Framework.
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Config\Patch\Append;
use Spiral\Core\Container\Autowire;
use Spiral\Scaffolder\Declaration;

class ScaffolderBootloader extends Bootloader
{
    /** @var ConfiguratorInterface */
    private $config;

    /**
     * @param ConfiguratorInterface $config
     */
    public function __construct(ConfiguratorInterface $config)
    {
        $this->config = $config;
    }

    public function boot(): void
    {
        $this->config->setDefaults('scaffolder', [
            /*
             * This is set of comment lines to be applied to every scaffolded file, you can use env() function
             * to make it developer specific or set one universal pattern per project.
             */
            'header'       => [
                '{project-name}',
                '',
                '@author {author-name}'
            ],

            /*
             * Base directory for generated classes, class will be automatically localed into sub directory
             * using given namespace.
             */
            'directory'    => directory('application') . 'classes/',

            /*
             * Default namespace to be applied for every generated class.
             *
             * Example: 'namespace' => 'MyApplication'
             * Controllers: MyApplication\Controllers\SampleController
             */
            'namespace'    => '',

            /*
             * This is set of default settings to be used for your scaffolding commands.
             */
            'declarations' => [
                'controller' => [
                    'namespace' => 'Controller',
                    'postfix'   => 'Controller',
                    'class'     => Declaration\ControllerDeclaration::class
                ],
                'middleware' => [
                    'namespace' => 'Middleware',
                    'postfix'   => '',
                    'class'     => Declaration\MiddlewareDeclaration::class
                ],
                'command'    => [
                    'namespace' => 'Command',
                    'postfix'   => 'Command',
                    'class'     => Declaration\CommandDeclaration::class
                ],
                'migration'  => [
                    'namespace' => '',
                    'postfix'   => 'Migration',
                    'class'     => Declaration\MigrationDeclaration::class
                ],
                'filter'     => [
                    'namespace' => 'Filter',
                    'postfix'   => 'Filter',
                    'class'     => Declaration\FilterDeclaration::class,
                    'options'   => [
                        //Set of default filters and validate rules for various types
                        'mapping' => [
                            'int'    => [
                                'source'    => 'data',
                                'setter'    => 'intval',
                                'validates' => ['notEmpty', 'integer']
                            ],
                            'float'  => [
                                'source'    => 'data',
                                'setter'    => 'floatval',
                                'validates' => ['notEmpty', 'float']
                            ],
                            'string' => [
                                'source'    => 'data',
                                'setter'    => 'strval',
                                'validates' => ['notEmpty', 'string']
                            ],
                            'bool'   => [
                                'source'    => 'data',
                                'setter'    => 'boolval',
                                'validates' => ['notEmpty', 'boolean']
                            ],
                            'email'  => [
                                'source'    => 'data',
                                'setter'    => 'strval',
                                'validates' => ['notEmpty', 'string', 'email']
                            ],
                            'file'   => [
                                'source'    => 'file',
                                'validates' => ['file::uploaded']
                            ],
                            'image'  => [
                                'source'    => 'file',
                                'validates' => ['image::uploaded', 'image::valid']
                            ],
                        ]
                    ]
                ],
//                'record'     => [
//                    'namespace' => 'Database',
//                    'postfix'   => '',
//                    'class'     => Declaration\Database\RecordDeclaration::class
//                ],
//                'document'   => [
//                    'namespace' => 'Database',
//                    'postfix'   => '',
//                    'class'     => Declaration\Database\DocumentDeclaration::class
//                ],
//                'source'     => [
//                    'namespace' => 'Database\Sources',
//                    'postfix'   => 'Source'
//                ],
            ],
        ]);

        $this->config->modify('tokenizer', new Append('directories', null, directory('vendor') . 'spiral/scaffolder/src/'));
    }
}