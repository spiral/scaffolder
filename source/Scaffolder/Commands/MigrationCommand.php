<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Scaffolder\Commands;

use Spiral\Migrations\Migrator;
use Spiral\Reactor\FileDeclaration;
use Spiral\Scaffolder\AbstractCommand;
use Spiral\Scaffolder\Declarations\MigrationDeclaration;
use Spiral\Scaffolder\Exceptions\ScaffolderException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrationCommand extends AbstractCommand
{
    /**
     * Element to be managed.
     */
    const ELEMENT = 'migration';

    /**
     * Command name and options.
     */
    const NAME        = 'create:migration';
    const DESCRIPTION = 'Create migration declaration';
    const ARGUMENTS   = [
        ['name', InputArgument::REQUIRED, 'Migration name'],
    ];

    /**
     * @param Migrator $migrator
     *
     * @throws ScaffolderException
     */
    public function perform(Migrator $migrator)
    {
        /** @var MigrationDeclaration $declaration */
        $declaration = $this->createDeclaration();

        if (!empty($this->option('table'))) {
            $columns = [];
            foreach ($this->option('column') as $field) {
                if (strpos($field, ':') === false) {
                    throw new ScaffolderException("Column definition must in 'name:type' form");
                }

                list($name, $type) = explode(':', $field);
                $columns[$name] = $type;
            }

            $declaration->declareCreation($this->option('table'), $columns);
        }

        $file = new FileDeclaration($this->getNamespace());
        $file->setComment($this->config->headerLines());

        $file->addElement($declaration);

        $filename = $migrator->getRepository()->registerMigration(
            $this->argument('name'),
            $declaration->getName(),
            $file->render()
        );

        $this->writeln(
            "Declaration of '<info>{$declaration->getName()}</info>' "
            . "has been successfully written into '<comment>{$filename}</comment>'."
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function defineOptions(): array
    {
        return [
            [
                'table',
                't',
                InputOption::VALUE_OPTIONAL,
                'Table to be created table'
            ],
            [
                'column',
                'f',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Create column in a format "name:type"'
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