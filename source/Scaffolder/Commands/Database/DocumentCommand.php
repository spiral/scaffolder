<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Scaffolder\Commands\Database;

use Spiral\ODM\Document;
use Spiral\Scaffolder\AbstractCommand;
use Spiral\Scaffolder\Declarations\Database\DocumentDeclaration;
use Spiral\Scaffolder\Exceptions\ScaffolderException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DocumentCommand extends AbstractCommand
{
    /**
     * Element to be managed.
     */
    const ELEMENT = 'document';

    /**
     * @var string
     */
    protected $name = 'create:document';

    /**
     * @var string
     */
    protected $description = 'Create new Document model';

    /**
     * @var array
     */
    protected $arguments = [
        ['name', InputArgument::REQUIRED, 'Document name']
    ];

    /**
     * Create controller declaration.
     */
    public function perform()
    {
        /**
         * @var DocumentDeclaration $declaration
         */
        $declaration = $this->createDeclaration([
            'parent' => Document::class
        ]);

        foreach ($this->option('field') as $field) {
            if (strpos($field, ':') === false) {
                throw new ScaffolderException("Field definition must in 'name:type' form.");
            }

            list($name, $type) = explode(':', $field);
            $declaration->declareField($name, $type);
        }

        $declaration->setCollection($this->option('collection'));
        $declaration->setDatabase($this->option('database'));

        $this->writeDeclaration($declaration->normalize());
    }

    /**
     * {@inheritdoc}
     */
    protected function defineOptions()
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
                'm',
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
            ]
        ];
    }
}