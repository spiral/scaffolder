<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Scaffolder\Commands;

use Spiral\Scaffolder\AbstractCommand;
use Spiral\Scaffolder\Declarations\ControllerDeclaration;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ControllerCommand extends AbstractCommand
{
    /**
     * Element to be managed.
     */
    const ELEMENT = 'controller';

    /**
     * @var string
     */
    protected $name = 'create:controller';

    /**
     * @var string
     */
    protected $description = 'Create controller declaration';

    /**
     * @var array
     */
    protected $arguments = [
        ['name', InputArgument::REQUIRED, 'Controller name']
    ];

    /**
     * Create controller declaration.
     */
    public function perform()
    {
        /**
         * @var ControllerDeclaration $declaration
         */
        $declaration = $this->createDeclaration();

        foreach ($this->option('action') as $action) {
            $declaration->action($action);
        }

        $this->writeDeclaration($declaration);
    }

    /**
     * {@inheritdoc}
     */
    protected function defineOptions()
    {
        return [
            [
                'action',
                'a',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Pre-create controller action'
            ],
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