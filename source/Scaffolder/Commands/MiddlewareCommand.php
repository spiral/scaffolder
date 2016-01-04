<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Scaffolder\Commands;

use Spiral\Scaffolder\AbstractCommand;
use Spiral\Scaffolder\Declarations\MiddlewareDeclaration;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MiddlewareCommand extends AbstractCommand
{
    /**
     * Element to be managed.
     */
    const ELEMENT = 'middleware';

    /**
     * @var string
     */
    protected $name = 'create:middleware';

    /**
     * @var string
     */
    protected $description = 'Create middleware declaration';

    /**
     * @var array
     */
    protected $arguments = [
        ['name', InputArgument::REQUIRED, 'Middleware name']
    ];

    /**
     * Create controller declaration.
     */
    public function perform()
    {
        /**
         * @var MiddlewareDeclaration $declaration
         */
        $declaration = $this->createDeclaration();

        $this->writeDeclaration($declaration);
    }

    /**
     * {@inheritdoc}
     */
    protected function defineOptions()
    {
        return [
//            [
//                'depends',
//                'i',
//                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
//                'Add dependency to class (type:name or full class name or short binding)'
//            ],
            [
                'comment',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Optional comment to add as class header'
            ]
        ];
    }
}