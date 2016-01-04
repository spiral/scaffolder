<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Scaffolder\Commands;

use Spiral\Reactor\ClassDeclaration\MethodDeclaration;
use Spiral\Scaffolder\AbstractCommand;
use Spiral\Scaffolder\Declarations\ServiceDeclaration;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ServiceCommand extends AbstractCommand
{
    /**
     * Element to be managed.
     */
    const ELEMENT = 'service';

    /**
     * @var string
     */
    protected $name = 'create:service';

    /**
     * @var string
     */
    protected $description = 'Create service/model declaration';

    /**
     * @var array
     */
    protected $arguments = [
        ['name', InputArgument::REQUIRED, 'Service/model name']
    ];

    /**
     * Create controller declaration.
     */
    public function perform()
    {
        /**
         * @var ServiceDeclaration $declaration
         */
        $declaration = $this->createDeclaration();

        foreach ($this->option('method') as $method) {
            $declaration->method($method)->setAccess(MethodDeclaration::ACCESS_PUBLIC);
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
                'method',
                'm',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Pre-create service/model method'
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