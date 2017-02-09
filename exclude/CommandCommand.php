<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Scaffolder\Commands;

use Spiral\Scaffolder\AbstractCommand;
use Spiral\Scaffolder\Declarations\CommandDeclaration;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CommandCommand extends AbstractCommand
{
    /**
     * Element to be managed.
     */
    const ELEMENT = 'command';

    /**
     * @var string
     */
    protected $name = 'create:command';

    /**
     * @var string
     */
    protected $description = 'Create command declaration';

    /**
     * @var array
     */
    protected $arguments = [
        ['name', InputArgument::REQUIRED, 'Command name'],
        ['alias', InputArgument::OPTIONAL, 'Command id/alias'],
    ];

    /**
     * Create controller declaration.
     */
    public function perform()
    {
        $alias = !empty($this->argument('alias')) ? $this->argument('alias') : $this->argument('name');

        /**
         * @var CommandDeclaration $declaration
         */
        $declaration = $this->createDeclaration(compact('alias'));

        $declaration->setDescription($this->option('description'));

        $this->writeDeclaration($declaration);
    }

    /**
     * {@inheritdoc}
     */
    protected function defineOptions()
    {
        return [
            [
                'description',
                'd',
                InputOption::VALUE_OPTIONAL,
                'Command description'
            ],
            [
                'comment',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Optional comment to add as class header'
            ]
        ];
    }
}