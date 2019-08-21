<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Command;

use Spiral\Scaffolder\Declaration\FilterDeclaration;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class FilterCommand extends AbstractCommand
{
    /**
     * Default input source.
     */
    private const DEFAULT_SOURCE = 'data';

    /**
     * Default type to apply.
     */
    private const   DEFAULT_TYPE = 'string';

    protected const ELEMENT = 'filter';

    protected const NAME        = 'create:request';
    protected const DESCRIPTION = 'Create RequestFilter declaration';
    protected const ARGUMENTS   = [
        ['name', InputArgument::REQUIRED, 'Request name'],
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
     *
     * @return array
     */
    private function parseField(string $field): array
    {
        $source = static::DEFAULT_SOURCE;
        $type = static::DEFAULT_TYPE;
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