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
     * Command name and options.
     */
    const NAME        = 'create:command';
    const DESCRIPTION = 'Create command declaration';
    const ARGUMENTS   = [
        ['name', InputArgument::REQUIRED, 'Command name'],
        ['alias', InputArgument::OPTIONAL, 'Command id/alias'],
    ];

    /**
     * Create command declaration.
     */
    public function perform()
    {
        /** @var CommandDeclaration $declaration */
        $declaration = $this->createDeclaration(compact('alias'));

        $declaration->setAlias($this->argument('alias') ?? $this->argument('name'));
        $declaration->setDescription((string)$this->option('description'));

        $this->writeDeclaration($declaration);
    }

    /**
     * {@inheritdoc}
     */
    protected function defineOptions(): array
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
