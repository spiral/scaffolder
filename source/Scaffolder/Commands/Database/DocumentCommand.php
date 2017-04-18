<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Scaffolder\Commands\Database;

use Spiral\Scaffolder\AbstractCommand;
use Spiral\Scaffolder\Commands\Database\Traits\SourceDeclarationTrait;
use Spiral\Scaffolder\Declarations\Database\DocumentDeclaration;
use Spiral\Scaffolder\Exceptions\ScaffolderException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DocumentCommand extends AbstractCommand
{
    use SourceDeclarationTrait;

    const ELEMENT = 'document';

    /**
     * Command name and options.
     */
    const NAME        = 'create:document';
    const DESCRIPTION = 'Create Document declaration';
    const ARGUMENTS   = [
        ['name', InputArgument::REQUIRED, 'Document name']
    ];

    /**
     * Create controller declaration.
     */
    public function perform()
    {
        /** @var DocumentDeclaration $declaration */
        $declaration = $this->createDeclaration();

        foreach ($this->option('field') as $field) {
            if (strpos($field, ':') === false) {
                throw new ScaffolderException("Field definition must in 'name:type' form");
            }

            list($name, $type) = explode(':', $field);
            $declaration->addField($name, $type);
        }

        $declaration->setCollection((string)$this->option('collection'));
        $declaration->setDatabase((string)$this->option('database'));

        $this->writeDeclaration($declaration->normalizeDeclaration());

        if ($this->option('source')) {
            $this->writeDeclaration(
                $this->sourceDeclaration(
                    $this->argument('name'),
                    'document',
                    $this->getNamespace() . '\\' . $this->getClass()
                ),
                'source'
            );
        }
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
                'collection',
                't',
                InputOption::VALUE_OPTIONAL,
                'Associated collection'
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
            ],
            [
                'source',
                's',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_NONE,
                'Create source/repository class'
            ]
        ];
    }
}