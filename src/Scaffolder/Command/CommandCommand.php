<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Command;

use Spiral\Scaffolder\Declaration\CommandDeclaration;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CommandCommand extends AbstractCommand
{
    protected const ELEMENT = 'command';

    protected const NAME        = 'create:command';
    protected const DESCRIPTION = 'Create command declaration';
    protected const ARGUMENTS   = [
        ['name', InputArgument::REQUIRED, 'Command name'],
        ['alias', InputArgument::OPTIONAL, 'Command id/alias'],
    ];
    protected const OPTIONS     = [
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

    /**
     * Create command declaration.
     */
    public function perform(): void
    {
        /** @var CommandDeclaration $declaration */
        $declaration = $this->createDeclaration();

        $declaration->setAlias($this->argument('alias') ?? $this->argument('name'));
        $declaration->setDescription((string)$this->option('description'));

        $this->writeDeclaration($declaration);
    }
}