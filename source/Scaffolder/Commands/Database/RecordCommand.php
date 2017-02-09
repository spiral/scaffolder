<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Scaffolder\Commands\Database;

use Spiral\Scaffolder\AbstractCommand;
use Spiral\Scaffolder\Declarations\Database\RecordDeclaration;
use Spiral\Scaffolder\Exceptions\ScaffolderException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RecordCommand extends AbstractCommand
{
    /**
     * Element to be managed.
     */
    const ELEMENT = 'record';

    /**
     * Command name and options.
     */
    const NAME        = 'create:record';
    const DESCRIPTION = 'Create Record declaration';
    const ARGUMENTS   = [
        ['name', InputArgument::REQUIRED, 'Record name']
    ];

    /**
     * Create controller declaration.
     */
    public function perform()
    {
        /** @var RecordDeclaration $declaration */
        $declaration = $this->createDeclaration();

        foreach ($this->option('field') as $field) {
            if (strpos($field, ':') === false) {
                throw new ScaffolderException("Field definition must in 'name:type' form");
            }

            list($name, $type) = explode(':', $field);
            $declaration->addField($name, $type);
        }

        $declaration->setTable((string)$this->option('table'));
        $declaration->setDatabase((string)$this->option('database'));

        $this->writeDeclaration($declaration->normalizeDeclaration());
    }

    /**
     * {@inheritdoc}
     */
    protected function defineOptions(): array
    {
        return [
            [
                'field',
                'f',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Add field in a format "name:type"'
            ],
            [
                'table',
                't',
                InputOption::VALUE_OPTIONAL,
                'Associated table'
            ],
            [
                'database',
                'db',
                InputOption::VALUE_OPTIONAL,
                'Associated database'
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