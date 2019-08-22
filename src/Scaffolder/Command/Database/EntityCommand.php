<?php
/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Command\Database;

use Spiral\Console\Console;
use Spiral\Reactor\AbstractDeclaration;
use Spiral\Scaffolder\Command\AbstractCommand;
use Spiral\Scaffolder\Declaration\Database\Entity\AnnotatedDeclaration;
use Spiral\Scaffolder\Exception\ScaffolderException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use function Spiral\Scaffolder\trimPostfix;

class EntityCommand extends AbstractCommand
{
    protected const ELEMENT = 'entity';

    protected const NAME        = 'create:entity';
    protected const DESCRIPTION = 'Create entity declaration';
    protected const ARGUMENTS   = [
        ['name', InputArgument::REQUIRED, 'Entity name'],
        ['format', InputArgument::OPTIONAL, 'Declaration format (annotated, xml?, yaml?, php?)', 'annotated'],
    ];
    protected const OPTIONS     = [
        [
            'role',
            'r',
            InputOption::VALUE_NONE,
            'Entity role, defaults to lowercase class name without a namespace'
        ],
        [
            'mapper',
            'm',
            InputOption::VALUE_NONE,
            'Mapper class name, defaults to Cycle\ORM\Mapper\Mapper'
        ],
        [
            'repository',
            'repo',
            InputOption::VALUE_NONE,
            'Repository class to represent read operations for an entity, defaults to Cycle\ORM\Select\Repository',
        ],
        [
            'table',
            't',
            InputOption::VALUE_OPTIONAL,
            'Entity source table, defaults to plural form of entity role'
        ],
        [
            'database',
            'db',
            InputOption::VALUE_OPTIONAL,
            'Database name, defaults to null (default database)'
        ],
        [
            'accessibility',
            'a',
            InputOption::VALUE_OPTIONAL,
            'Optional visibility accessor (public, protected, private), defaults to public',
            AbstractDeclaration::ACCESS_PUBLIC,
        ],
        [
            'inflection',
            'i',
            InputOption::VALUE_OPTIONAL,
            'Optional column name inflection, allowed values: tableize (t), camelize (c)'
        ],
        [
            'field',
            'f',
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Add field in a format "name:type"'
        ],
        [
            'comment',
            'c',
            InputOption::VALUE_OPTIONAL,
            'Optional comment to add as class header'
        ]
    ];

    /**
     * @param Console $console
     * @throws \Throwable
     */
    public function perform(Console $console): void
    {
        $accessibility = (string)$this->option('accessibility');
        $this->validateAccessibility($accessibility);

        /** @var AnnotatedDeclaration $declaration */
        $declaration = $this->createDeclaration();

        $repository = trimPostfix((string)$this->option('repository'), 'repository');

        $declaration->setRole((string)$this->option('role'));
        $declaration->setMapper((string)$this->option('mapper'));
        $declaration->setRepository($repository);
        $declaration->setTable((string)$this->option('table'));
        $declaration->setDatabase((string)$this->option('database'));
        $declaration->setInflection((string)$this->option('inflection'));

        foreach ($this->option('field') as $field) {
            if (strpos($field, ':') === false) {
                throw new ScaffolderException("Field definition must in 'name:type' or 'name:type' form");
            }

            $parts = explode(':', $field);
            [$name, $type] = $parts;

            $declaration->addField($name, $accessibility, $type);
        }

        $declaration->finalize();

        $this->writeDeclaration($declaration);

        if ($this->option('repository')) {
            $console->run('create:repository', [
                'name' => !empty($repository) ? $repository : $this->argument('name')
            ]);
        }
    }

    protected function declarationClass(string $element): string
    {
        return $this->config->declarationOptions($element)[$this->argument('format')];
    }

    /**
     * @param string $accessibility
     */
    private function validateAccessibility(string $accessibility): void
    {
        if (!in_array($accessibility, [
            AbstractDeclaration::ACCESS_PUBLIC,
            AbstractDeclaration::ACCESS_PROTECTED,
            AbstractDeclaration::ACCESS_PRIVATE
        ], true)) {
            throw new ScaffolderException("Invalid accessibility value `$accessibility`");
        }
    }
}