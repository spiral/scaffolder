<?php

/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Anton Titov (Wolfy-J)
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Command;

use Spiral\Scaffolder\Declaration\FilterDeclaration;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class FilterCommand extends AbstractCommand
{
    protected const ELEMENT = 'filter';

    protected const NAME        = 'create:filter';
    protected const DESCRIPTION = 'Create filter declaration';
    protected const ARGUMENTS   = [
        ['name', InputArgument::REQUIRED, 'filter name'],
    ];
    protected const OPTIONS     = [
        [
            'field',
            'f',
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Input field in a format "field:type(source:origin)" or "field(source)".'
        ],
        [
            'comment',
            'c',
            InputOption::VALUE_OPTIONAL,
            'Optional comment to add as class header'
        ]
    ];

    /**
     * Create filter declaration.
     */
    public function perform(): void
    {
        /** @var FilterDeclaration $declaration */
        $declaration = $this->createDeclaration();

        foreach ($this->option('field') as $field) {
            [$field, $type, $source, $origin] = $this->parseField($field);
            $declaration->declareField($field, $type, $source, $origin);
        }

        $this->writeDeclaration($declaration);
    }

    /**
     * Parse field to fetch source, origin and type.
     *
     * @param string $field
     * @return array
     */
    private function parseField(string $field): array
    {
        $type = null;
        $source = null;
        $origin = null;

        if (strpos($field, '(') !== false) {
            $source = substr($field, strpos($field, '(') + 1, -1);
            $field = substr($field, 0, strpos($field, '('));

            if (strpos($source, ':') !== false) {
                [$source, $origin] = explode(':', $source);
            }
        }

        if (strpos($field, ':') !== false) {
            [$field, $type] = explode(':', $field);
        }

        return [$field, $type, $source, $origin];
    }
}
