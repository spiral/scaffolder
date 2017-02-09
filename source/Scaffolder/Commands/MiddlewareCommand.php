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
     * Command name and options.
     */
    const NAME        = 'create:middleware';
    const DESCRIPTION = 'Create middleware declaration';
    const ARGUMENTS   = [
        ['name', InputArgument::REQUIRED, 'Middleware name']
    ];

    /**
     * Create middleware declaration.
     */
    public function perform()
    {
        /** @var MiddlewareDeclaration $declaration */
        $declaration = $this->createDeclaration();
        $this->writeDeclaration($declaration);
    }

    /**
     * {@inheritdoc}
     */
    protected function defineOptions(): array
    {
        return [
            [
                'comment',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Optional comment to add as class header'
            ]
        ];
    }
}