<?php
/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Command;

use Spiral\Scaffolder\Declaration\ConfigDeclaration;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ConfigCommand extends AbstractCommand
{
    protected const ELEMENT = 'config';

    protected const NAME        = 'create:config';
    protected const DESCRIPTION = 'Create config declaration';
    protected const ARGUMENTS   = [
        ['name', InputArgument::REQUIRED, 'config name']
    ];
    protected const OPTIONS     = [
        [
            'comment',
            'c',
            InputOption::VALUE_OPTIONAL,
            'Optional comment to add as class header'
        ]
    ];

    /**
     * Create config declaration.
     */
    public function perform(): void
    {
        /** @var ConfigDeclaration $declaration */
        $declaration = $this->createDeclaration(['configName' => $this->argument('name')]);

        $this->writeDeclaration($declaration);
    }
}
